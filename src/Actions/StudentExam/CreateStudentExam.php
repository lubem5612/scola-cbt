<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\StudentExam;

class CreateStudentExam
{
    use ResponseHelper, ValidationHelper;

    private $request, $validatedData, $uploader;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->setAttempt();
            $this->setStatus();
            $this->setStartTime();
            return $this->assignUserToExam();
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
    }

    private function assignUserToExam()
    {
        $studentExam = StudentExam::query()->create($this->validatedData);
        return $this->sendSuccess($studentExam, 'student assigned to exams successfully');
    }

    private function setAttempt()
    {
        $examsCount = StudentExam::query()->where([
            'student_id' => $this->validatedData['student_id'],
            'exam_id' => $this->validatedData['exam_id'],
        ])->count();
        $this->validatedData['attempts'] = $examsCount + 1;
    }

    private function setStatus()
    {
        if (!Arr::exists($this->validatedData, 'status')) {
            $this->validatedData['status'] = 'ongoing';
        }
    }

    private function setStartTime()
    {
        if (!Arr::exists($this->validatedData, 'start_time')) {
            $this->validatedData['start_time'] = Carbon::now();
        }else {
            $this->validatedData['start_time'] = Carbon::parse($this->validatedData['start_time']);
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'student_id' => 'required|exists:cbt_students,id',
            'exam_id' => 'required|exists:cbt_exams,id',
            'attempts' => 'sometimes|required|integer',
            'status' => 'nullable|string|in:ongoing,terminated,completed',
            'start_time' => 'nullable|date',
        ]);
    }
}