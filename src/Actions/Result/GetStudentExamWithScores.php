<?php

namespace Transave\ScolaCbt\Actions\Result;

use Illuminate\Contracts\Database\Eloquent\Builder;
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
            return $this->sendSuccess($this->calculatedData, 'result details for student returned');
        }catch (\Exception $exception) {
            return $this->sendServerError($exception);
        }
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

            $item['exam'] = Exam::query()->select('id as exam_id', 'exam_name', 'exam_mode')
                ->where('id', $this->validatedData['exam_id'])->first();

            $examId = $this->validatedData['exam_id'];
            $answers =  Answer::query()
                ->where('user_id', $this->validatedData['user_id'])
                ->where('attempts', $attempt['attempts'])
                ->whereHas('question',function (Builder $builder) use ($examId) {
                    $builder->where('exam_id', $examId);
                })->get();

            foreach ($answers as $answer) {
                if (!empty($answer) && $answer->isCorrectOption()) {
                    $scores = $scores + (float)$answer->question->score_obtainable;
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
            ])->select('attempts')->distinct();
    }

    private function validateRequest()
    {
        $this->validatedData = $this->validate($this->request, [
            'user_id' => 'required|exists:users,id',
            'exam_id' => 'required|exists:exams,id',
        ]);
    }
}