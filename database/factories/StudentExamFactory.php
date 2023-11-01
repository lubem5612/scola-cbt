<?php

namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\StudentExam;

class StudentExamFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = StudentExam::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'student_id' => Student::factory(),
            'exam_id' => Exam::factory(),
        ];
    }
}