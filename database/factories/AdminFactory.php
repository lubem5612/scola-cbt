<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Admin;
use Transave\ScolaCbt\Http\Models\User;

class AdminFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Admin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'address' => $this->faker->address,
            'next_of_kin' => $this->faker->name,
            'highest_qualification' => $this->faker->word,
            'date_of_birth' => $this->faker->time,
            'retirement_date' => $this->faker->time
        ];
    }
}