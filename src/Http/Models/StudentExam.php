<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\StudentExamFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class StudentExam extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "student_exams";

    protected $guarded = [
        "id"
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }


    protected static function newFactory()
    {
        return StudentExamFactory::new();
    }
}