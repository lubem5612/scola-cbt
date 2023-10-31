<?php


namespace Transave\ScolaCbt\Tests\Feature\Result;


use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Actions\Result\CalculateBatchExamScores;
use Transave\ScolaCbt\Actions\Result\CalculateExamScore;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\Course;
use Transave\ScolaCbt\Http\Models\Department;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Http\Models\Session;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\StudentExam;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class CalculateStudentResultTest extends TestCase
{
    private $user, $exams;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->user);

        $student = Student::factory()->create(['user_id' => $this->user->id]);

        $options = Option::factory()->count(4);
        $questions = Question::factory()->count(10)
            ->has($options);
        $this->exams = Exam::factory()->count(3)
            ->for(User::factory()->create())
            ->for(Course::factory()->create())
            ->for(Department::factory()->create())
            ->for(Session::factory()->create())
            ->has($questions)
            ->create();
        foreach ($this->exams as $exam) {
            StudentExam::query()->create([
                'student_id' => $student->id,
                'exam_id' => $exam->id
            ]);
        }
        foreach (Question::all() as $question) {
            Answer::factory()->for($this->user)->create([
                'question_id' => $question->id,
                'option_id' => $question->options->first()->id
            ]);
        }
    }

    /** @test */
    function can_calculate_single_exam_scores_for_student()
    {
        $exam = Exam::query()->first();
        $response = (new CalculateExamScore(['user_id' => $this->user->id, 'exam_id' => $exam->id]))->execute();
        $this->assertTrue($response['success']);
        $this->assertNotNull($response['data']);
    }

    /** @test */
    function can_calculate_batch_exam_scores_for_student()
    {
        $response = (new CalculateBatchExamScores(['user_id' => $this->user->id]))->execute();
        $this->assertTrue($response['success']);
        $this->assertNotNull($response['data']);
    }

    /** @test */
    function can_calculate_batch_exam_scores_in_a_session_for_student()
    {
        $session = Session::query()->first();
        $response = (new CalculateBatchExamScores(['user_id' => $this->user->id, 'session_id'=> $session->id ]))->execute();
        $this->assertTrue($response['success']);
        $this->assertNotNull($response['data']);
    }

    /** @test */
    function can_calculate_batch_exam_scores_in_a_session_and_semester_for_student()
    {
        $session = Session::query()->first();
        $response = (new CalculateBatchExamScores(['user_id' => $this->user->id, 'session_id'=> $session->id, 'semester' => 'First']))->execute();
        $this->assertTrue($response['success']);
        $this->assertNotNull($response['data']);
    }
}