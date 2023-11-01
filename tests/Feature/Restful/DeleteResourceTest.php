<?php


namespace Transave\ScolaCbt\Tests\Feature\Restful;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\StudentExam;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class DeleteResourceTest extends TestCase
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

    public function can_delete_specified_session()
    {
        Session::factory()->count(10)->create();
        $session = Session::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/sessions/{$session->id}");

        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_faculty()
    {
        Faculty::factory()->count(10)->create();
        $faculty = Faculty::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/faculties/{$faculty->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_department()
    {
        Department::factory()->count(10)->create();
        $department = Department::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/departments/{$department->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_course()
    {
        Course::factory()->count(10)->create();
        $course = Course::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/courses/{$course->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

    /** @test */
    public function can_delete_specified_question_option()
    {
        Option::factory()->count(10)->create();
        $option = Option::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/question-options/{$option->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }


    /** @test */
    public function can_delete_specified_student_exams()
    {
        StudentExam::factory()->count(10)->create();
        $studentexams = StudentExam::query()->inRandomOrder()->first();
        $response = $this->json('DELETE', "/cbt/general/student-exams/{$studentexams->id}");
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
    }

}