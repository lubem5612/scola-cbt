<?php


namespace Transave\ScolaCbt\Tests\Feature\User;


use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\Staff;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Tests\TestCase;

class UpdateUserTest extends TestCase
{
    private $faker, $user, $request;
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
        $this->testData();
    }

    /** @test */
    function can_update_user_account()
    {
        $user = config('scola-cbt.auth_model')::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/cbt/users/{$user->id}", $this->request);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_change_authenticated_user_email()
    {
        $data = [
            'email' => $this->faker->email,
        ];
        $response = $this->json('PATCH', "/cbt/users/change-email", $data);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_change_authenticated_user_password()
    {
        $data = [
            'old_password' => 'password',
            'password' => Str::random(10)
        ];
        $response = $this->json('PATCH', "/cbt/users/change-password", $data);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    private function testData()
    {
        $this->request = [
            'user_id' => $this->user->id,
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'registration_number' => $this->faker->randomDigit(),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'department_id' => Department::factory()->create()->id,
            'photo' => UploadedFile::fake()->image('photo.jpeg'),
            'current_level' => $this->faker->randomElement([1,2,3,4,5,6])
        ];
    }
}