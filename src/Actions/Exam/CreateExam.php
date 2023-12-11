<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Illuminate\Support\Carbon;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Session;

class CreateExam
{
    use ResponseHelper, ValidationHelper;
    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setUser()
                ->setSession()
                ->setMaximumScore()
                ->createExam();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function createExam()
    {
        $exam = Exam::query()->create($this->request);
        return $this->sendSuccess($exam->load('user', 'course', 'faculty', 'department', 'session'), 'exam created successfully');
    }

    private function setSession() : self
    {
        if (!array_key_exists('session_id', $this->request)) {
            $this->request['session_id'] = Session::query()->where('is_active', 1)->first()->id;
        }
        return $this;
    }

    private function setUser() : self
    {
        if (!array_key_exists('user_id', $this->request)) {
            $this->request['user_id'] = auth()->id();
        }
        return $this;
    }

    private function setMaximumScore() : self
    {
        if (!array_key_exists('max_score_obtainable', $this->request)) {
            $this->request['max_score_obtainable'] = config('scola-cbt.max_score_obtainable');
        }
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'user_id' => 'sometimes|required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'faculty_id' => 'required|exists:faculties,id',
            'department_id' => 'required|exists:departments,id',
            'session_id' => 'sometimes|required|exists:sessions,id',
            'semester' => 'required|string|max:50',
            'level' => 'required|string|max:20',
            'exam_name' => 'required|string|max:250',
            'max_score_obtainable' => 'sometimes|required|integer',
            'exam_mode' => 'sometimes|required|string|max:80',
            'start_time' => 'nullable|date_format:H:i',
            'duration' => 'sometimes|required|numeric',
            'unit_of_time' => 'sometimes|required|in:minute,hour',
            'exam_date' => 'nullable',
            'instruction' => 'sometimes|required|string',
            'venue' => 'sometimes|required|string',
        ]);

        if (array_key_exists('duration', $this->request) && array_key_exists('unit_of_time', $this->request)) {
            $duration = $this->request['duration'];
            $unitOfTime = $this->request['unit_of_time'];

            // Validate and convert duration to minutes or hours
            if ($unitOfTime === 'minute') {
                $this->validate($this->request, [
                    'duration' => 'integer|min:60',
                ]);
                $stopTime = Carbon::parse($this->request['start_time'])->addMinutes($duration);
            } elseif ($unitOfTime === 'hour') {
                $this->validate($this->request, [
                    'duration' => 'integer|min:1',
                ]);
                $stopTime = Carbon::parse($this->request['start_time'])->addHours($duration);
            }

            // Set stop_time in the request
            $this->request['stop_time'] = $stopTime;
        }
        return $this;
    }
}