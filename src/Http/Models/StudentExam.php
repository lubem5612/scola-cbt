<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\StudentExamFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class StudentExam extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "studentexams";

    protected $guarded = [
        "id"
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    protected static function newFactory()
    {
        return StudentExamFactory::new();
    }
}