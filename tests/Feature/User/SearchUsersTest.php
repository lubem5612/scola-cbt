<?php


namespace Transave\ScolaCbt\Tests\Feature\User;


use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Tests\TestCase;

class SearchUsersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    function can_search_answers_using_email_as_query_parameters()
    {
        $email = 'testing3455@ymail.com';
        $this->testData($email);
        $response = $this->json('GET', "/cbt/users?search={$email}");

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

    private function testData($email=null, $first_name=null, $last_name=null)
    {
        $faker = Factory::create();
        config('scola-cbt.auth_model')::factory()
            ->count(3)->create([
                'email' => is_null($email)? $faker->email : $email,
                'first_name' => is_null($first_name)? $faker->name : $first_name,
                'last_name' => is_null($last_name)? $faker->name : $last_name,
            ]);

        config('scola-cbt.auth_model')::factory()->count(3)->create();
    }
}