<?php

namespace Exams;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Tests\TestCase;

class GetExamWithStudentsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Exam::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    public function can_get_exam_with_students()
    {
        $exam = Exam::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/exams/students/{$exam->id}");
        $array = json_decode($response->getContent(), true);

        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);

        // Add assertions for exam details
        $this->assertEquals($array['data']['user_id'], $exam->user_id);
        $this->assertEquals($array['data']['faculty_id'], $exam->faculty_id);
        $this->assertEquals($array['data']['course_id'], $exam->course_id);
        $this->assertEquals($array['data']['department_id'], $exam->department_id);
        $this->assertEquals($array['data']['session_id'], $exam->session_id);

        // Add assertions for students
        $this->assertArrayHasKey('user', $array['data']);
        $this->assertIsArray($array['data']['user']);
        $this->assertNotEmpty($array['data']['user']);
        // Add more specific assertions based on your actual data structure
        // For example, check if user details match the associated student
    }
}

