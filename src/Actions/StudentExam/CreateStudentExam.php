<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Illuminate\Foundation\Auth\User;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\StudentExam;

class CreateStudentExam
{
    use ResponseHelper, ValidationHelper;

    private $request, $validatedData, $uploader, $student;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setStudent();
            $this->setAttempt();
            return $this->assignUserToExam();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function setStudent()
    {
        $this->student = Student::query()->find($this->validatedData['user_id']);
    }

    private function assignUserToExam()
    {
        $studentExam = StudentExam::query()->create($this->validatedData);
        return $this->sendSuccess($studentExam, 'student assigned to exams successfully');
    }

    private function setAttempt()
    {
        $examsCount = StudentExam::query()->where([
            'student_id' => $this->student->id,
            'exam_id' => $this->validatedData['exam_id'],
        ])->count();
        $this->validatedData['attempts'] = $examsCount + 1;
    }

    private function validateRequest()
    {
        $this->validatedData = $this->request($this->request, [
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'attempts' => 'sometimes|required|integer'
        ]);
    }
}