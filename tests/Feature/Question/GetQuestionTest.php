<?php

namespace Transave\ScolaCbt\Tests\Feature\Question;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class GetQuestionTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Question::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_get_question_with_specific_id()
    {
        $question = Question::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/questions/{$question->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['exam_id'], $question->exam_id);
    }
}