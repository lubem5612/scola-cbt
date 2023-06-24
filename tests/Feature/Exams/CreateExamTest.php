<?php


namespace Transave\ScolaCbt\Tests\Feature\Exams;


use Faker\Factory;
use Illuminate\Foundation\Auth\User;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Actions\Exam\CreateExam;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Tests\TestCase;

class CreateExamTest extends TestCase
{

    private $request, $faker;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->testData();
        $user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($user);
    }

    /** @test */
    public function can_create_exam_via_action()
    {
        $response = (new CreateExam($this->request))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_create_exam_via_api()
    {
        $response = $this->json('POST', 'cbt/exams', $this->request, ['Accept' => 'application/json']);
        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertEquals(true, $response['success']);
    }

    private function testData()
    {
        $this->request = [
            'user_id' => config('scola-cbt.auth_model')::factory()->create()->id,
            'course_id' => Course::factory()->create()->id,
            'department_id' => Department::factory()->create()->id,
            'session_id' => Session::factory()->create()->id,
            'semester' => $this->faker->randomElement(config('scola-cbt.semesters')),
            'level' => $this->faker->randomElement(config('scola-cbt.levels')),
            'exam_type' => $this->faker->randomElement(config('scola-cbt.question_type')),
            'max_score_obtainable' => 100,
            'exam_mode' => $this->faker->randomElement(config('scola-cbt.exam_mode')),
            'duration' => $this->faker->randomDigit(),
            'start_date' => $this->faker->time,
            'end_date' => $this->faker->time,
            'instruction' => $this->faker->sentence(20),
            'venue' => $this->faker->city,
        ];
    }
}