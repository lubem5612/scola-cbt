<?php


namespace Transave\ScolaCbt\Actions\Exam;


use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamDepartment;
use Transave\ScolaCbt\Http\Models\Session;

class UpdateExam
{
    use ResponseHelper, ValidationHelper;
    private $request;
    private Exam $exam;

    public function __construct(array $request)
    {
        $this->request = $request;
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
                ->setDepartments()
                ->updateExam();
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function updateExam()
    {
        $this->exam->fill($this->request)->save();
        return $this->sendSuccess($this->exam->refresh()->load('user', 'course', 'departments', 'session'), 'exam updated successfully');
    }

    private function setExam() : self
    {
        $this->exam = Exam::query()->find($this->request['exam_id']);
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

    private function setDepartments() : self
    {
        if (Arr::exists($this->request, 'department_ids')
            && is_array($this->request['department_ids'])
            && count($this->request['department_ids']) > 0) {

            ExamDepartment::query()->where('exam_id', $this->exam->id)->delete();
            foreach ($this->request['department_ids'] as $department_id) {
                ExamDepartment::query()->create([
                    'exam_id' => $this->exam->id,
                    'department_id' => $department_id
                ]);
            }
        }
        return $this;
    }

    private function validateRequest() : self
    {
        $this->validate($this->request, [
            'exam_id' => 'required|exists:cbt_exams,id',
            'user_id' => 'sometimes|required|exists:fc_users,id',
            'course_id' => 'sometimes|required|exists:cbt_courses,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'sometimes|required|exists:cbt_departments,id',
            'session_id' => 'sometimes|required|exists:cbt_sessions,id',
            'semester' => 'sometimes|required|string|max:50',
            'level' => 'sometimes|required|string|max:20',
            'exam_name' => 'sometimes|required|string|max:250',
            'max_score_obtainable' => 'sometimes|required|integer',
            'exam_mode' => 'sometimes|required|string|max:80',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'exam_date' => 'nullable',
            'instruction' => 'sometimes|required|string',
            'venue' => 'sometimes|required|string',
        ]);

        return $this;
    }
}