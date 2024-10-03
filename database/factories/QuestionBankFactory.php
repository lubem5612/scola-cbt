<?php


namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\QuestionBank;
use Transave\ScolaCbt\Http\Models\Session;

class QuestionBankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionBank::class;
    
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'level' => $this->faker->randomElement(['100', '200', '300', '400', '500', '600']),
            'session_id' => Session::factory()
        ];
    }
}