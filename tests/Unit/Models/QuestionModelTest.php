<?php

namespace Models;

use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class QuestionModelTest extends TestCase
{
    private $question;
    public function setUp(): void
    {
        parent::setUp();
        $this->question = Question::factory()->create();
    }

    /** @test */
    public function exam_model_can_be_initiated_with_factory()
    {
        $this->assertTrue($this->question instanceof Question);
    }
}
