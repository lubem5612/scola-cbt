<?php


namespace Transave\ScolaCbt\Tests\Feature\Answer;


use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Tests\TestCase;

class SearchAnswersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_answers_using_query_parameters()
    {
        $searchTerm = 'computer';
        $this->testData($searchTerm);
        $response = $this->json('GET', "/cbt/answers?search={$searchTerm}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }

    /** @test */
    function can_search_answers_using_time_intervals()
    {
        $start = Carbon::yesterday(); $end = Carbon::tomorrow();
        $this->testData();
        $response = $this->json('GET', "/cbt/answers?start={$start}&end={$end}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(6, $array['data']);
    }

    /** @test */
    function can_fetch_paginated_answers()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json('GET', "/cbt/answers?per_page={$perPage}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(2, $array['data']['data']);
    }

    private function testData($search=null)
    {
        $faker = Factory::create();
        Answer::factory()
            ->count(3)
            ->for(config('scola-cbt.auth_model')::factory()->state(['first_name' => $faker->name.' '.$search]))
            ->create();

        Answer::factory()
            ->count(3)
            ->for(config('scola-cbt.auth_model')::factory()->create())
            ->create();
    }
}