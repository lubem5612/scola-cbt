<?php

namespace Exams;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Tests\TestCase;

class GetExamTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Exam::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_get_exams_with_specific_id()
    {
        $exam = Exam::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/exams/{$exam->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['user_id'], $exam->user_id);
        $this->assertEquals($array['data']['faculty_id'], $exam->faculty_id);
        $this->assertEquals($array['data']['course_id'], $exam->course_id);
        $this->assertEquals($array['data']['department_id'], $exam->department_id);
        $this->assertEquals($array['data']['session_id'], $exam->session_id);
    }
}