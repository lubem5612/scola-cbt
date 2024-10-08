<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaCbt\Database\Factories\StudentFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Student extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "cbt_students";

    protected $guarded = [
        "id"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class, 'user_id', 'user_id');
    }

    public function studentExams() : HasMany
    {
        return $this->hasMany(StudentExam::class, 'student_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'cbt_student_exams', 'student_id', 'exam_id');
    }

    protected static function newFactory()
    {
        return StudentFactory::new();
    }

    public function getExamsTakenAttribute()
    {
        return $this->exams()->get();
    }

    protected $appends = [
        'exams_taken'
    ];
}