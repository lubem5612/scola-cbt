<?php

namespace Transave\ScolaCbt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;
use Transave\ScolaCbt\Models\Exam;

class Question extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'questions';

    protected $guarded = [
        'id'
    ];

    public function Exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
