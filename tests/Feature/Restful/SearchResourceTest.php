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

class SearchResourceTest extends TestCase
{
    private $user, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = User::factory()->create(['email' => 'sampledata@test.com', 'password' => bcrypt('sample1234'),]);
        Sanctum::actingAs($this->user);
    }

//    /** @test */
//
//    function can_get_sessions()
//    {
//        Session::factory()->count(10)->create();
//        $response = $this->json('GET', '/cbt/general/sessions');
//        $response->assertStatus(200);
//
//        $arrayData = json_decode($response->getContent(), true);
//        $this->assertEquals(true, $arrayData['success']);
//        $this->assertNotNull($arrayData['data']);
//    }
//
//    /** @test */
//    function can_get_faculties()
//    {
//        Faculty::factory()->count(10)->create();
//        $response = $this->json('GET', '/cbt/general/faculties');
//        $response->assertStatus(200);
//
//        $arrayData = json_decode($response->getContent(), true);
//        $this->assertEquals(true, $arrayData['success']);
//        $this->assertNotNull($arrayData['data']);
//    }
//
//    /** @test */
//    function can_get_departments()
//    {
//        Department::factory()
//            ->count(3)
//            ->for(Faculty::factory()->create())
//            ->create();
//
//        $response = $this->json('GET', '/cbt/general/departments');
//        $response->assertStatus(200);
//
//        $arrayData = json_decode($response->getContent(), true);
//        $this->assertEquals(true, $arrayData['success']);
//        $this->assertNotNull($arrayData['data']);
//    }
//
//    /** @test */
//    function can_get_courses()
//    {
//        Course::factory()->count(10)->create();
//        $response = $this->json('GET', '/cbt/general/courses');
//        $response->assertStatus(200);
//
//        $arrayData = json_decode($response->getContent(), true);
//        $this->assertEquals(true, $arrayData['success']);
//        $this->assertNotNull($arrayData['data']);
//    }

    /** @test */
    function can_get_question_options()
    {
        $question = Question::factory()->create();
        Option::factory()
            ->count(3)
            ->for($question)
            ->create();

        $response = $this->json('GET', '/cbt/general/question-options');
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}