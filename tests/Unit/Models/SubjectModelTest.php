<?php


namespace Transave\ScolaCbt\Tests\Unit\Models;


use Transave\ScolaCbt\Tests\TestCase;
use Transave\ScolaCbt\Models\Subject;

class SubjectModelTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_check_if_question_model_exists()
    {
        $this->assertTrue(class_exists(Subject::class), 'Subject model does not exist.');
    }

    public function test_can_populate_subjects_table()
    {
        $count = 0;
        foreach (range(1, 20) as $subject) {
            Subject::factory()->create();
            ++$count;
        }
        $this->assertEquals(20, $count);

    }
}