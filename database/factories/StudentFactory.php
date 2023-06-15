<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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

        ];
    }
}