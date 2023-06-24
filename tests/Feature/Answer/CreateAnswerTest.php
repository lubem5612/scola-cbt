<?php


namespace Transave\ScolaCbt\Tests\Feature\Answer;


use Faker\Factory;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Http\Models\User;
use Transave\ScolaCbt\Tests\TestCase;

class CreateAnswerTest extends TestCase
{
    private $user, $faker, $request;
    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->user = config('scola-cbt.auth_model')::factory()->create(['role' => 'admin']);
        Sanctum::actingAs($this->user);
        $this->testData();
    }

    /** @test */

    public function can_create_answer()
    {
        $response = $this->json('POST', '/cbt/answers', $this->request);
        $response->assertStatus(200);

        $arrayData = json_decode($response->getContent(), true);
        $this->assertEquals(true, $arrayData['success']);
        $this->assertNotNull($arrayData['data']);
    }

    private function testData()
    {
        $this->request = [
            'user_id' => config('scola-cbt.auth_model')::factory()->create()->id,
            'question_id' => Question::factory()->create()->id,
            'option_id' => Option::factory()->create()->id,
            'content' => $this->faker->sentence(10),
            'file' => UploadedFile::fake()->create('file.pdf', 2000, 'application/pdf')
        ];
    }
}