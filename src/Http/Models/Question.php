<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\QuestionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Question extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'questions';

    protected $guarded = [
        'id'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }


    protected static function newFactory()
    {
        return QuestionFactory::new();
    }
}
