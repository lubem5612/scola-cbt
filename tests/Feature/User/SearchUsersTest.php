<?php


namespace Transave\ScolaCbt\Tests\Feature\User;


use Carbon\Carbon;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
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
    function can_search_users_using_email_as_query_parameters()
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
    function can_search_users_using_first_name_as_query_parameters()
    {
        $name = 'Loko';
        $this->testData(null, $name);
        $response = $this->json('GET', "/cbt/users?search={$name}");
        $array = json_decode($response->getContent(), true);

        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }

    /** @test */
    function can_search_users_using_last_name_as_query_parameters()
    {
        $name = 'Monday';
        $this->testData(null, null, $name);
        $response = $this->json('GET', "/cbt/users?search={$name}");
        $array = json_decode($response->getContent(), true);

        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(3, $array['data']);
    }

    /** @test */
    function can_search_users_using_time_intervals()
    {
        $start = Carbon::yesterday(); $end = Carbon::tomorrow();
        $this->testData();
        $response = $this->json('GET', "/cbt/users?start={$start}&end={$end}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(6, $array['data']);
    }

    /** @test */
    function can_fetch_paginated_users_successfully()
    {
        $perPage = 2;
        $this->testData();
        $response = $this->json('GET', "/cbt/users?per_page={$perPage}");

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
        $this->assertCount(2, $array['data']['data']);
    }

    private function testData($email=null, $first_name=null, $last_name=null)
    {
        foreach (range(1,3) as $item) {
            $faker = Factory::create();
            config('scola-cbt.auth_model')::factory()->create([
                    'email' => is_null($email)? $item.$faker->email : $item.$email,
                    'first_name' => is_null($first_name)? $item.$faker->name : $item.$first_name,
                    'last_name' => is_null($last_name)? $faker->name : $item.$last_name,
                ]);
        }

        foreach (range(1, 3) as $item) {
            config('scola-cbt.auth_model')::factory()->create();
        }
    }
}