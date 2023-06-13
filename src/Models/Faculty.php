<?php


namespace Transave\ScolaCbt\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\FacultyFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Faculty extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'faculties';

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return FacultyFactory::new();
    }
}