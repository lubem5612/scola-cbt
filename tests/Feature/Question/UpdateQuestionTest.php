<?php

namespace Transave\ScolaCbt\Tests\Feature\Question;

use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class UpdateQuestionTest extends TestCase
{
    private $user, $faker, $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->user);
        Question::factory()->count(10)->create();
        $this->faker = Factory::create();
        $this->testData();
    }

    /** @test */

    public function can_update_question()
    {
        $question = Question::first();
        $response = $this->json('POST', "/cbt/questions/{$question->id}", $this->request);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    private function testData(){
        $question = Question::first();
        $this->request = [
            'question_id' => $question->id,
            'exam_id' => Exam::factory()->create()->id,
            'question_type' => $this->faker->randomElement(config('scola-cbt.question_type')),
            'score_obtainable' => 100,
            'question' =>$this->faker->sentence(20),
            'file' => UploadedFile::fake()->create('file.pdf', 2000, 'application/pdf'),
            'answers' => $this->faker->sentence(20)
        ];
    }

}