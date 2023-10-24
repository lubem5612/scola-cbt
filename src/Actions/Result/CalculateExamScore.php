<?php


namespace Transave\ScolaCbt\Actions\Result;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;

class CalculateExamScore
{
    use ResponseHelper, ValidationHelper;
    private $request, $student, $user, $exams;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            return $this
                ->validateRequest()
                ->createStudentExam();
        } catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    private function getStudentUser()
    {
        $this->user = config('scola-cbt.auth_model')::query()->find($this->request['user_id']);
        $this->student = $this->user->student;
        return $this;
    }

    private function getExams()
    {
        $this->exams = $this->student->exams->load('options');
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        return $this;
    }
}