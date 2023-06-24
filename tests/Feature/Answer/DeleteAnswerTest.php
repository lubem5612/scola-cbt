<?php


namespace Transave\ScolaCbt\Tests\Feature\Answer;


use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Tests\TestCase;

class DeleteAnswerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Answer::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_delete_answer_successfully()
    {
        $answer = Answer::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/answers/{$answer->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNull($arrayData['data']);
    }
}