<?php

namespace Transave\ScolaCbt\Tests\Feature\Exams;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Tests\TestCase;

class ExamLinkTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Exam::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    public function can_generate_exam_link()
    {
        $exam = Exam::query()->inRandomOrder()->first();

        $response = $this->json('POST', "/cbt/exams/exam-link/{$exam->id}");
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }

}
