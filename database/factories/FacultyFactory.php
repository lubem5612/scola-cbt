<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Models\Faculty;

class FacultyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Faculty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name
        ];
    }
}