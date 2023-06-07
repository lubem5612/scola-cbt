<?php
namespace Transave\ScolaCbt\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Models\Exam;
use Transave\ScolaCbt\Models\User;
use Carbon\Carbon;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'exam_type' => $this->faker->randomElement(['Graded', 'Mock']),
            'session' => $this->faker->date(),
            'semester' => $this->faker->randomElement(['First', 'Second']),
            'total_score' => $this->faker->numberBetween(10, 100),
            'faculty' => $this->faker->word,
            'department' => $this->faker->word,
            'level' => $this->faker->randomElement(['100', '200', '300', '400', '500', '600']),
            'exam_mode' => $this->faker->randomElement(['Multiple Choice', 'Essay']),
            'answer_type' => $this->faker->randomElement(['Multiple Choice', 'Essay']),
            'duration' => $this->faker->time(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'instruction' => $this->faker->sentence,
            'venue' => $this->faker->word,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
