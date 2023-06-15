<?php

namespace Models;

use Transave\ScolaCbt\Http\Models\Question;
use Transave\ScolaCbt\Tests\TestCase;

class QuestionModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_check_if_question_model_exists()
    {
        $this->assertTrue(class_exists(Question::class), 'Question model does not exist.');
    }
}
