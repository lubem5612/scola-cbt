<?php


namespace Transave\ScolaCbt\Tests\Feature\Exams;


use Transave\ScolaCbt\Actions\Exam\GetExam;
use Transave\ScolaCbt\Actions\Exam\SearchExam;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;
use function Symfony\Component\Uid\Factory\create;

class SearchExamTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Exam::factory()->count(20)
            ->for(User::factory()->create())
            ->for(Course::factory()->create())
            ->for(Department::factory()->create())
            ->for(Session::factory()->create())
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