<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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