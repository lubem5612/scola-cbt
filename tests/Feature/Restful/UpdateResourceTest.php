<?php


namespace Transave\ScolaCbt\Tests\Feature\Restful;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class UpdateResourceTest extends TestCase
{
    private $user, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create(['email' => 'sampledata@test.com', 'password' => bcrypt('sample1234'),]);
        Sanctum::actingAs($this->user);
    }

    /** @test */

    function can_update_specified_session()
    {
        $data = [
            'name' => $this->faker->name,
            'is_active' => $this->faker->randomElement(['no', 'yes'])
        ];
        Session::factory()->count(10)->create();
        $session = Session::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/cbt/general/sessions/{$session->id}", $data, ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_faculty()
    {
        $data = [
            'name' => $this->faker->name
        ];
        Faculty::factory()->count(10)->create();
        $faculty = Faculty::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/cbt/general/faculties/{$faculty->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_department()
    {
        $data = [
            'name' => $this->faker->name,
        ];
        Department::factory()->count(10)->create();
        $department = Department::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "/cbt/general/departments/{$department->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_course()
    {
        $data = [
            'credit_load' => rand(1, 6),
            'code' => $this->faker->countryCode.'-'.$this->faker->randomDigit()
        ];
        Course::factory()->count(10)->create();
        $course = Course::query()->inRandomOrder()->first();
        $response = $this->json('POST', "/cbt/general/courses/{$course->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_update_specified_question_option()
    {
        $data = [
            'content' => $this->faker->sentence,
            'is_correct_option' => $this->faker->randomElement(['no', 'yes'])
        ];
        Option::factory()->count(10)->create();
        $option = Option::query()->inRandomOrder()->first();
        $response = $this->json('PATCH', "/cbt/general/question-options/{$option->id}", $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}