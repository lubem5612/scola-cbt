<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamDepartment;

class ExamDepartmentFactory  extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */

    protected $model = ExamDepartment::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'department_id' => Department::factory(),
            'exam_id' => Exam::factory(),
        ];
    }
}