<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaCbt\Database\Factories\ExamFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Exam extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "exams";

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'faculty_ids'
    ];

    public function session() : BelongsTo
    {
        return  $this->belongsTo(Session::class);
    }


    public function course() : BelongsTo
    {
        return  $this->belongsTo(Course::class);
    }

    public function departments() : BelongsToMany
    {
        return  $this->BelongsToMany(Department::class, 'exam_departments', 'exam_id', 'department_id');
    }

    public function user() : BelongsTo
    {
        return  $this->belongsTo(User::class);
    }

    public function questions() : HasMany
    {
        return $this->hasMany(Question::class)->with(['options', 'answers']);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_exams', 'exam_id', 'student_id');
    }

    public function getFacultyIdsAttribute()
    {
        return $this->departments()->get()->pluck('faculty_id');
    }

    protected static function newFactory()
    {
        return ExamFactory::new();
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}