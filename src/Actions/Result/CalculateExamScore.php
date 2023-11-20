<?php


namespace Transave\ScolaCbt\Actions\Result;


use Illuminate\Support\Facades\Log;
use Transave\ScolaCbt\Helpers\ValidationHelper;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\StudentExam;

class CalculateExamScore
{
    use ValidationHelper;
    private $request, $student, $user, $exam, $questions, $scores=0;
    private $isSuccess = false, $responseMessage = '';

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            $this->validateRequest();
            $this->getStudentUser();
            $this->getExamAnsQuestions();
            $this->calculateScores();
        } catch (\Exception $e) {
            Log::error($e);
            $this->responseMessage = $e->getMessage();
        }
        return $this->buildResponse();
    }

    private function getStudentUser()
    {
        $this->user = config('scola-cbt.auth_model')::query()->find($this->request['user_id']);
        $this->student = $this->user->student;
        return $this;
    }

    private function getExamAnsQuestions()
    {
        $studentExams = StudentExam::query()
            ->where('student_id', $this->student->id)
            ->where('exam_id', $this->request['exam_id'])
            ->first();
        $this->exam = $studentExams?->exam;
        $this->questions = $this?->exam?->questions;
    }

    private function calculateScores()
    {
        $scores = 0;
        if (!empty($this->questions)) {
            foreach ($this->questions as $question)
            {
                $answer = Answer::query()->where([
                    'question_id' => $question->id,
                    'user_id' => $this->user->id,
                ])->first();

                if (!empty($answer) && $answer->isCorrectOption()) {
                    $scores = $scores + (float)$question->score_obtainable;
                }
            }
        }
        $this->scores = $scores;
        $this->responseMessage = 'scores returned successfully';
        $this->isSuccess = true;
    }

    private function buildResponse()
    {
        return [
            'success' => $this->isSuccess,
            'message' => $this->responseMessage,
            'data' => $this->scores,
        ];
    }

    private function validateRequest()
    {
        $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
        ]);

        return $this;
    }
}