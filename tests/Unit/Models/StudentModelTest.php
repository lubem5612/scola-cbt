<?php

namespace Models;

use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Tests\TestCase;

class StudentModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_check_if_student_model_exists()
    {
        $this->assertTrue(class_exists(Student::class), 'Student model does not exist.');
    }
}