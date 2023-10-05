<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Question;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'exam_id' => Exam::factory(),
            'question_type' => $this->faker->randomElement(config('scola-cbt.question_type')),
            'score_obtainable' => rand(50, 100),
            'question' => $this->faker->sentence(20),
            'file' => UploadedFile::fake()->create('exam.pdf', 200, 'application/pdf'),
            'answers' =>  $this->faker->sentence(20)
        ];
    }
}