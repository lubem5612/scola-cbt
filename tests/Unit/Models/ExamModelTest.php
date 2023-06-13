<?php

namespace Models;

use Transave\ScolaCbt\Models\Exam;
use Transave\ScolaCbt\Tests\TestCase;

class ExamModelTest extends TestCase
{
    private $exam;
    public function setUp(): void
    {
        parent::setUp();
        $this->exam = Exam::factory()->create();
    }

    /** @test */
    public function exam_model_can_be_initiated_with_factory()
    {
        $this->assertTrue($this->exam instanceof Exam);
    }

}
