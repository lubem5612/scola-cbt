<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\StudentExam;

class UpdateStudentExam
{
    use ResponseHelper, ValidationHelper;

    private $request, $validatedData, $uploader;
    private ?StudentExam $studentExam;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setStudentExam();
            return $this->updateUserToExam();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function updateUserToExam()
    {
        $this->studentExam->fill($this->validatedData)->save();
        return $this->sendSuccess($this->studentExam->refresh(), 'student exams updated successfully');
    }

    private function setStudentExam()
    {
        $this->studentExam = StudentExam::query()->find($this->validatedData['student_exam_id']);
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'student_exam_id' => 'required|exists:student_exams',
            'status' => 'sometimes|required|in:ongoing,terminated,completed',
            'end_time' => 'nullable|date'
        ]);
    }
}