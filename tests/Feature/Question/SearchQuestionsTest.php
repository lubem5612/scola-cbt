<?php

namespace Transave\ScolaCbt\Tests\Feature\Question;

use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Actions\Question\GetQuestion;
use Transave\ScolaCbt\Actions\Question\SearchQuestion;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class SearchQuestionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
        Question::factory()->count(10)
            ->for(Exam::factory()->create())
            ->create();
    }

    /** @test */
    public function can_search_question_via_action()
    {
        $response = (new SearchQuestion(Question::class, []))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_search_question_via_action_with_relationship()
    {
        $response = (new SearchQuestion(Question::class, ['Exam']))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_search_single_question_via_action_with_relationship()
    {
        $question = Question::query()->inRandomOrder()->first();
        $response = (new GetQuestion(['id' => $question->id]))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }
}
