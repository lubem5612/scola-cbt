<?php


namespace Transave\ScolaCbt\Tests\Feature\Exams;


use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Actions\Exam\GetExam;
use Transave\ScolaCbt\Actions\Exam\SearchExam;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class SearchExamTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->user);

        $questions = Question::factory()->count(3)
            ->has(Option::factory()->count(4))
            ->has(Answer::factory()->count(4));
        Exam::factory()->count(2)
            ->for(User::factory()->create())
            ->for(Course::factory()->create())
            ->for(Department::factory()->create())
            ->for(Session::factory()->create())
            ->has($questions)
            ->create();
    }

    /** @test */
    public function can_search_exams_via_action()
    {
        $response = (new SearchExam(Exam::class, []))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_search_exams_via_api()
    {
        $response = $this->json('GET', "/cbt/exams");
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_search_exams_via_action_with_relationship()
    {
        $response = (new SearchExam(Exam::class, ['user', 'course', 'department', 'session']))->execute();
        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }

    /** @test */
    public function can_search_single_exam_via_action_with_relationship()
    {
        $exam = Exam::query()->inRandomOrder()->first();
        $response = (new GetExam(['id' => $exam->id]))->execute();

        $array = json_decode($response->getContent(), true);
        $this->assertEquals(true, $array['success']);
        $this->assertNotNull($array['data']);
    }
}