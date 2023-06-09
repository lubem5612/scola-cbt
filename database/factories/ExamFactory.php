<?php
namespace Transave\ScolaCbt\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\User;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'user_id' => config('scola-cbt.auth_model')::factory(),
            'course_id' => Course::factory(),
            'department_id' => Department::factory(),
            'session_id' => Session::factory(),
            'semester' => $this->faker->randomElement(config('scola-cbt.semesters')),
            'level' => $this->faker->randomElement(config('scola-cbt.levels')),
            'exam_type' => $this->faker->randomElement(config('scola-cbt.question_type')),
            'max_score_obtainable' => 100,
            'exam_mode' => $this->faker->randomElement(config('scola-cbt.exam_mode')),
            'duration' => $this->faker->randomDigit(),
            'start_date' => $this->faker->time,
            'end_date' => $this->faker->time,
            'instruction' => $this->faker->sentence(20),
            'venue' => $this->faker->city,
        ];
    }
}
