<?php


namespace Transave\ScolaCbt\Tests\Feature\Auth;


use Faker\Factory;
use Transave\ScolaCbt\Actions\Auth\RegisterExaminer;
use Transave\ScolaCbt\Actions\Auth\RegisterManager;
use Transave\ScolaCbt\Actions\Auth\RegisterStaff;
use Transave\ScolaCbt\Actions\Auth\RegisterStudent;
use Transave\ScolaCbt\Actions\Auth\RegisterUser;
use Transave\ScolaCbt\Tests\TestCase;

class RegisterTest extends TestCase
{
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->getTestData();
    }
    /** @test */
    public function can_register_user()
    {
        $response = (new RegisterUser($this->request))->execute();
        $this->assertEquals(true, $response['success']);
        $this->assertNotNull($response['data']);
    }

    /** @test */
    public function can_register_staff()
    {
        $response = (new RegisterStaff($this->request))->execute();
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('data', $json);

        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    /** @test */
    public function can_register_examiner()
    {
        $response = (new RegisterExaminer($this->request))->execute();
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('data', $json);

        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    /** @test */
    public function can_register_student()
    {
        $response = (new RegisterStudent($this->request))->execute();
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('data', $json);

        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    /** @test */
    public function can_register_manager()
    {
        $response = (new RegisterManager($this->request))->execute();
        $json = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('success', $json);
        $this->assertArrayHasKey('data', $json);

        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    /** @test */
    public function can_register_account_successfully()
    {
        $response = $this->json('POST', route('cbt.register'), $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);

        $json = json_decode($response->getContent(), true);
        $this->assertEquals(true, $json['success']);
        $this->assertNotNull($json['data']);
    }

    private function getTestData()
    {
        $faker = Factory::create();
        $this->request = [
            'email' => $faker->email,
            'first_name' => $faker->name,
            'last_name' => $faker->name,
            'role' => $faker->randomElement(['student', 'staff', 'admin', 'manager', 'examiner']),
            'password' => 'password1234',
            'phone_number' => $faker->phoneNumber,
            'address' => $faker->address,
        ];
    }

}