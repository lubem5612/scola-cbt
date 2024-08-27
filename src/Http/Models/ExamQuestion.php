<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\ExamQuestionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class ExamQuestion extends Model
{
    use HasFactory, UUIDHelper;
    
    protected $table = "cbt_exam_questions";
    
    protected $guarded = [
        "id"
    ];
    
    protected $hidden = ['created_at', 'updated_at'];
    
    public function exam() : BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
    
    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    
    protected static function newFactory()
    {
        return ExamQuestionFactory::new();
    }
}