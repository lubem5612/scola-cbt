<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Staff;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-cbt.auth_model')::factory(),
            'department_id' => Department::factory(),
            'phone' => $this->faker->phoneNumber,
            'photo' => UploadedFile::fake()->image('profile.jpg'),
            'address' => $this->faker->sentence
        ];
    }
}