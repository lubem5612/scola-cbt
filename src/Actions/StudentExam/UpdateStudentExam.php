<?php


namespace Transave\ScolaCbt\Actions\StudentExam;


use Carbon\Carbon;
use Illuminate\Support\Arr;
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
            $this->formatStartTime();
            $this->formatEndTime();
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

    private function formatStartTime()
    {
        if (Arr::exists($this->validatedData, 'start_time') && $this->validatedData['start_time']) {
            $this->validatedData['start_time'] = Carbon::parse($this->validatedData['start_time']);
        }
    }
    private function formatEndTime()
    {
        if (Arr::exists($this->validatedData, 'end_time') && $this->validatedData['end_time']) {
            $this->validatedData['end_time'] = Carbon::parse($this->validatedData['end_time']);
        }
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'student_exam_id' => 'required|exists:student_exams,id',
            'status' => 'sometimes|required|in:ongoing,terminated,completed',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date'
        ]);
    }
}