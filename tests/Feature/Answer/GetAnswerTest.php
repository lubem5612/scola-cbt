<?php


namespace Transave\ScolaCbt\Tests\Feature\Answer;


use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Tests\TestCase;

class GetAnswerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Answer::factory()->count(20)->create();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_get_answer_with_specific_id()
    {
        $answer = Answer::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/answers/{$answer->id}");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertEquals($array['data']['user_id'], $answer->user_id);
        $this->assertEquals($array['data']['option_id'], $answer->option_id);
        $this->assertEquals($array['data']['question_id'], $answer->question_id);
    }
}