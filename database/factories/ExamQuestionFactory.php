<?php


namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamQuestion;
use Transave\ScolaCbt\Http\Models\Question;

class ExamQuestionFactory extends Factory
{
    protected $model = ExamQuestion::class;
    
    public function definition()
    {
        return [
            'question_id' => Question::factory(),
            'exam_id' => Exam::factory(),
        ];
    }
}