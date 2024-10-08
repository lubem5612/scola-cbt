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
            $result = StudentExam::query()->join('cbt_students', 'cbt_student_exams.student_id', '=', 'cbt_students.id')
                ->join('fc_users', 'fc_users.id', '=', 'cbt_students.user_id')
                ->join('cbt_exams', 'cbt_exams.id', '=', 'cbt_student_exams.exam_id')
                ->select('cbt_exams.*', 'fc_users.first_name', 'fc_users.last_name', 'cbt_students.id as student_id', 'fc_users.email')
                ->where('cbt_student_exams.attempts', $attempt['attempts'])->first();

//            $data = $this->validatedData;
//            $scores = Answer::query()->where(function ($query) use($data, $attempt) {
//                $query->where('user_id', $data['user_id'])
//                    ->where('attempts', $attempt['attempts'])
//                    ->whereHas('question', function ($secondQuery) use ($data, $attempt) {
//                        $secondQuery->where('exam_id', $data['exam_id'])
//                            ->whereHas('options', function ($thirdQuery) {
//                                $thirdQuery->where('is_correct_option', 'yes');
//                            });
//                    });
//            })->count();

            $exam = Exam::query()->find($this->validatedData['exam_id']);
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

            $result['exam_scores'] = $scores;
            if (!empty(collect($result)) && isset($result)) {
                array_push($this->calculatedData, $result);
            }
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
            'user_id' => 'required|exists:fc_users,id',
            'exam_id' => 'required|exists:cbt_exams,id',
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
