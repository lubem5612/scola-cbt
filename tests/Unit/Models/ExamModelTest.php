<?php

namespace Models;

use Orchestra\Testbench\TestCase;
use Transave\ScolaCbt\Models\Exam;

class ExamModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_check_if_exam_model_exists()
    {
        $this->assertTrue(class_exists(Exam::class), 'Exam model does not exist.');
    }
}
