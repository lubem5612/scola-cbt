<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\User;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

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
            'registration_number' => $this->faker->randomDigit(),
            'current_level' => rand(1, 6),
            'photo' => UploadedFile::fake()->image('profile.jpg'),
            'address' => $this->faker->sentence
        ];
    }
}