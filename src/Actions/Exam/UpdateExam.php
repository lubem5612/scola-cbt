<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Session;

class UpdateExam
{
    use ResponseHelper, ValidationHelper;
    private $request, $exam, $id;

    public function __construct(array $request, string $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->setExam()
                ->setUser()
                ->setSession()
                ->setMaximumScore()
                ->updateExam();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateExam()
    {
        $this->exam->fill($this->request)->save();
        return $this->sendSuccess($this->exam->refresh()->load('user', 'course', 'department', 'session'), 'exam updated successfully');
    }

    private function setExam() : self
    {
        $this->exam = Exam::query()->find($this->id);
        return $this;
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
            'course_id' => 'sometimes|required|exists:courses,id',
            'department_id' => 'sometimes|required|exists:departments,id',
            'session_id' => 'sometimes|required|exists:sessions,id',
            'semester' => 'sometimes|required|string|max:50',
            'level' => 'sometimes|required|string|max:20',
            'exam_type' => 'sometimes|required|string',
            'max_score_obtainable' => 'sometimes|required|integer',
            'exam_mode' => 'sometimes|required|string|max:80',
            'duration' => 'nullable',
            'start_date' => 'nullable',
            'end_date' => 'nullable',
            'instruction' => 'sometimes|required|string',
            'venue' => 'sometimes|required|string',
        ]);

        return $this;
    }
}