<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;

class OptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Option::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question_id' => Question::factory(),
            'is_correct_option' => $this->faker->randomElement(['yes', 'no']),
            'content' => $this->faker->sentence
        ];
    }
}