<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\ExamFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Exam extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "exams";

    protected $guarded = [
        'id'
    ];

    protected static function newFactory()
    {
        return ExamFactory::new();
    }
}