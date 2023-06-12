<?php


namespace Transave\ScolaCbt\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaCbt\Database\Factories\SubjectFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Subject extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'subjects';

    protected $guarded = [
        "id"
    ];

    public function exams(): HasMany
    {
        return  $this->hasMany(Exam::class);
    }

//    protected static function newFactory()
//    {
//        return SubjectFactory::new();
//    }
}