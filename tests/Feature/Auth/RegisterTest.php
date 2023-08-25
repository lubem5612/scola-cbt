<?php


namespace Transave\ScolaCbt\Tests\Feature\Auth;


use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Actions\Auth\Register;
use Transave\ScolaCbt\Http\Models\Department;
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
    public function can_register_account_action()
    {
        $response = (new Register($this->request))->execute();
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
            'phone' => $faker->phoneNumber,
            'registration_number' => $faker->randomDigit(),
            'department_id' => Department::factory()->create()->id,
            'address' => $faker->address,
            'photo' => UploadedFile::fake()->image('pic.jpg'),
        ];
    }

}