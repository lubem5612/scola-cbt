<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\QuestionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Question extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'cbt_questions';

    protected $guarded = [
        'id'
    ];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'cbt_exam_questions', 'question_id', 'exam_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }


    protected static function newFactory()
    {
        return QuestionFactory::new();
    }
}
