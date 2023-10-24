<?php


namespace Transave\ScolaCbt\Actions\StudentExam;



use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\StudentExam;

class CreateStudentExam
{

    use ResponseHelper, ValidationHelper;

    private array $request;

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

    private function createStudentExam()
    {
        $studentExam = StudentExam::create([
            'student_id' => $this->request['student_id'],
            'exam_id' => $this->request['course_id'],
        ]);

        return $this->sendSuccess($studentExam, 'Stored Student exam successfully');
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