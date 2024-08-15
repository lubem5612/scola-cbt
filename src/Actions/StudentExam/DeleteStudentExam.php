<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\StudentExam;

class DeleteStudentExam
{
    use ValidationHelper, ResponseHelper;
    private array $request;
    private array $validatedData;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            return $this->deleteUserExam();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function deleteUserExam()
    {
        StudentExam::destroy($this->validatedData['student_user_id']);
        return $this->sendSuccess(null, 'student exam deleted');
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'student_exam_id' => 'required|exists:cbt_student_exams,id'
        ]);
    }
}