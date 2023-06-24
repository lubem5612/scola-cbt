<?php

namespace Transave\ScolaCbt\Tests\Feature\Question;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class DeleteQuestionTest extends TestCase
{
    private $faker, $request;

    public function setUp(): void
    {
        parent::setUp();
        Question::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    public function can_delete_question_successfully(){
        $question = Question::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/questions/{$question->id}");
        $response->assertStatus(200);
        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNull($arrayData['data']);
    }
}