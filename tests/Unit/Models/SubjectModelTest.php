<?php


namespace Transave\ScolaCbt\Tests\Unit\Models;


use Orchestra\Testbench\TestCase;
use Transave\ScolaCbt\Models\Subject;

class SubjectModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
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