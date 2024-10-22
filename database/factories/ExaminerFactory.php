<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\User;

class ExaminerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Examiner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'department_id' => Department::factory(),
            'phone' => $this->faker->phoneNumber,
            'photo' => UploadedFile::fake()->image('profile.jpg')
        ];
    }
}