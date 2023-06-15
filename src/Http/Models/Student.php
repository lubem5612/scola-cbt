<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\StudentFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Student extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "students";

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return StudentFactory::new();
    }
}