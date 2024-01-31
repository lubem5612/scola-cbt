<?php

namespace Transave\ScolaCbt\Actions\Result;

use Illuminate\Support\Facades\Log;
use Transave\ScolaCbt\Helpers\ResponseHelper;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\StudentExam;

class GetStudentExamWithScores
{
    use ValidationHelper, ResponseHelper;
    private $request, $validatedData, $userExams, $attempts, $calculatedData = [], $student;
    private $responseMessage = '', $responseStatus = false;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try{
            $this->validateRequest();
            $this->setStudent();
            $this->getDistinctAttempts();
            $this->calculateExamData();
            $this->responseMessage = 'result details for student returned';
            $this->responseStatus = true;
        }catch (\Exception $exception) {
            Log::error($exception);
            $this->responseMessage = $exception->getMessage();
        }

        return $this->buildResponse();
    }

    private function setStudent()
    {
        $this->student = Student::query()->where('user_id', $this->validatedData['user_id'])->first();
    }

    private function calculateExamData()
    {
        $scores = 0;
        foreach ($this->attempts as $attempt) {
            $item['candidate'] = config('scola-cbt.auth_model')::query()->with(['student'])->find($this->validatedData['user_id']);

            $exam = Exam::query()->find($this->validatedData['exam_id']);
            $item['exam'] = $exam;
            $questions = $exam->questions;

            foreach ($questions as $question) {
                $answer = Answer::query()->where([
                    'user_id' => $this->validatedData['user_id'],
                    'question_id' => $question->id,
                    'attempts' => $attempt['attempts'],
                ])->first();

                if (!empty($answer) && $answer->isCorrectOption()) {
                    $scores = $scores + (float)$question->score_obtainable;
                }

            }

            $item['scores'] = $scores;
            array_push($this->calculatedData, $item);
        }
    }

    private function getDistinctAttempts()
    {
        $this->attempts = StudentExam::query()
            ->where([
                'student_id' => $this->student->id,
                'exam_id' => $this->validatedData['exam_id']
            ])->select('attempts')->distinct()->get();
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
        ]);
    }

    private function buildResponse()
    {
        return [
            'success' => $this->responseStatus,
            'message' => $this->responseMessage,
            'data' => $this->calculatedData,
        ];
    }
}
