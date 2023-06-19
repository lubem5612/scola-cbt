<?php


namespace Transave\ScolaCbt\Tests\Feature\Restful;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class GetResourceTest extends TestCase
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

    function can_get_specified_session()
    {
        Session::factory()->count(10)->create();
        $session = Session::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/general/sessions/{$session->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_get_specified_faculty()
    {
        Faculty::factory()->count(10)->create();
        $faculty = Faculty::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/general/faculties/{$faculty->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_get_specified_department()
    {
        Department::factory()
            ->count(3)
            ->for(Faculty::factory()->create())
            ->create();

        $department = Department::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/general/departments/{$department->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_get_specified_course()
    {
        Course::factory()->count(10)->create();
        $course = Course::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/general/courses/{$course->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    function can_get_specified_question_option()
    {
        $question = Question::factory()->create();
        Option::factory()
            ->count(3)
            ->for($question)
            ->create();

        $option = Option::query()->inRandomOrder()->first();
        $response = $this->json('GET', "/cbt/general/question-options/{$option->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}