<?php


namespace Transave\ScolaCbt\Tests\Feature\Restful;


use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Faculty;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class CreateResourceTest extends TestCase
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

    public function can_create_session()
    {
        $data = [
            'name' => $this->faker->name,
            'is_active' => $this->faker->randomElement(['no', 'yes'])
        ];
        $response = $this->json('POST', '/cbt/general/sessions', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_create_faculty()
    {
        $data = [
            'name' => $this->faker->name
        ];
        $response = $this->json('POST', '/cbt/general/faculties', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_create_department()
    {
        $faculty = Faculty::factory()->create();
        $data = [
            'name' => $this->faker->name,
            'faculty_id' => $faculty->id,
        ];
        $response = $this->json('POST', '/cbt/general/departments', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_create_course()
    {
        $data = [
            'name' => $this->faker->name,
            'credit_load' => rand(1, 6),
            'code' => $this->faker->countryCode.'-'.$this->faker->randomDigit(),
            'department_id' => Department::factory()->create()->id,
        ];
        $response = $this->json('POST', '/cbt/general/courses', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_create_question_option()
    {
        $question = Question::factory()->create();
        $data = [
            'content' => $this->faker->sentence,
            'question_id' => $question->id,
            'is_correct_option' => $this->faker->randomElement(['no', 'yes'])
        ];
        $response = $this->json('POST', '/cbt/general/question-options', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    /** @test */
    public function can_create_studentexams()
    {
        $data = [
            'student_id' => Student::factory()->create()->id,
            'exam_id' => Exam::factory()->create()->id,
        ];
        $response = $this->json('POST', '/cbt/general/student-exams', $data, ['Accept' => 'application/json']);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }
}