<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\StudentExamFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class StudentExam extends Model
{
    use HasFactory, UUIDHelper;
    protected $table = "cbt_student_exams";

    protected $guarded = [
        "id"
    ];

    protected $appends = [
        'exam_score', 'essay_score', 'total_score'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->with(['user']);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function getExamScoreAttribute()
    {
        $scores = 0;
        $questions = $this->exam()->first()->questions;
        $student = Student::query()->find($this->student_id);
        foreach ($questions as $question) {
            $answer = Answer::query()->where([
                'user_id' => $student->user_id,
                'question_id' => $question->id,
                'attempts' => $this->attempts,
            ])->first();

            if (!empty($answer) && $answer->isCorrectOption()) {
                $scores = $scores + (float)$question->score_obtainable;
            }
        }

        return $scores;
    }

    public function getEssayScoreAttribute()
    {
        $scores = 0;
        $questions = $this->exam()->first()->questions;
        $student = Student::query()->find($this->student_id);
        foreach ($questions as $question) {
            $answer = Answer::query()->where([
                'user_id' => $student->user_id,
                'question_id' => $question->id,
                'attempts' => $this->attempts,
            ])->first();

            if (!empty($answer)) {
                $scores = $scores + (float)$answer->score;
            }
        }

        return $scores;
    }

    public function getTotalScoreAttribute()
    {
        return (float)$this->getEssayScoreAttribute() + (float)$this->getExamScoreAttribute();
    }

    protected static function newFactory()
    {
        return StudentExamFactory::new();
    }
}