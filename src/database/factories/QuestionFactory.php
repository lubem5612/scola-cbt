<?php

namespace Transave\ScolaCbt\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Models\Exam;
use Transave\ScolaCbt\Models\Question;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'exam_id' => Exam::factory(),
            'subject' => $this->faker->word,
            'question_type' => $this->faker->randomElement(['Multiple Choice', 'Essay']),
            'unit_score' => $this->faker->numberBetween(1, 5),
            'question' => $this->faker->sentence,
            'images' => null,
            'answers' => json_encode([
                'option1' => $this->faker->sentence,
                'option2' => $this->faker->sentence,
                'option3' => $this->faker->sentence,
                'option4' => $this->faker->sentence,
            ]),
        ];
    }
}