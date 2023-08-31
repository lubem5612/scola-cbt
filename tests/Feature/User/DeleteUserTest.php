<?php

namespace Transave\ScolaCbt\Tests\Feature\User;

use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\Staff;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class DeleteUserTest extends TestCase
{
    private $faker, $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = config('scola-cbt.auth_model')::factory()->create();
        config('scola-cbt.auth_model')::factory()->has(Student::factory(), 'student')->create();
        config('scola-cbt.auth_model')::factory()->has(Manager::factory(), 'manager')->create();
        config('scola-cbt.auth_model')::factory()->has(Examiner::factory(), 'examiner')->create();
        config('scola-cbt.auth_model')::factory()->has(Staff::factory(), 'staff')->create();
        Sanctum::actingAs($this->user);
    }

    /** @test */
    public function can_delete_user_successfully()
    {
        if (User::count() === 0) {
            $this->markTestSkipped('No users available for deletion.');
        }

        $user = User::inRandomOrder()->first();
        $response = $this->json('DELETE', "cbt/users/{$user->id}", ['user_id' => $user->id]);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertTrue($arrayData['success']);
        $this->assertNull($arrayData['data']);
    }

}
