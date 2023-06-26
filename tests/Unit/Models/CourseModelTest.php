<?php


namespace Transave\ScolaCbt\Tests\Unit\Models;


use Transave\ScolaCbt\Tests\TestCase;
use Transave\ScolaCbt\Http\Models\Course;

class CourseModelTest extends TestCase
{
    private $course;
    public function setUp(): void
    {
        parent::setUp();
        $this->course = Course::factory()->create();
    }

    /** @test */
    public function course_model_can_be_initiated_with_factory()
    {
        $this->assertTrue($this->course instanceof Course);
    }

    /** @test */
    public function courses_table_exists_in_database()
    {
        $this->assertModelExists($this->course);
    }
}