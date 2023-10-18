<?php

namespace Transave\ScolaCbt\Tests\Feature\StudentExam;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Tests\TestCase;

class CreateStudentExamTest extends TestCase
{

    private $request, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->testData();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'student']);
        Sanctum::actingAs($user);
    }

    /** @test */
    public function can_create_studentExam_via_action()
    {
        $response = $this->json('POST', '/cbt/studentexams', $this->request);
        dd($response);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertArrayHasKey('data', $arrayData);
        $this->assertNotNull($arrayData['data']);
    }

    private function testData()
    {
        $this->request = [
            'student_id' => Student::factory()->create()->id,
            'course_id' => Course::factory()->create()->id,
            'exams' => [
                'user_id' => config('scola-cbt.auth_model')::factory()->create()->id,
                'course_id' =>  Course::factory()->create()->id,
                'faculty_id' => Faculty::factory()->create()->id,
                'department_id' => Department::factory()->create()->id,
                'session_id' => Session::factory()->create()->id,
                'semester' => $this->faker->randomElement(config('scola-cbt.semesters')),
                'level' => $this->faker->randomElement(config('scola-cbt.levels')),
                'exam_name' => $this->faker->name,
                'max_score_obtainable' => 100,
                'exam_mode' => $this->faker->randomElement(config('scola-cbt.exam_mode')),
                'start_time' => $this->faker->time('H:i'),
                'end_time' => $this->faker->time('H:i'),
                'exam_date' => $this->faker->date,
                'instruction' => $this->faker->sentence(20),
                'venue' => $this->faker->city,
            ],
        ];
    }
}