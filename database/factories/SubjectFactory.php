<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Models\Subject;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'credit_load' => rand(1, 6)
        ];
    }
}