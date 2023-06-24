<?php

namespace Transave\ScolaCbt\Tests\Feature\Question;

use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
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
    function can_search_question_using_via_action()
    {
        $response = (new SearchQuestion(Question::class, []))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }



}