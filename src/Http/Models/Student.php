<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\StudentFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Student extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "students";

    protected $guarded = [
        "id"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function newFactory()
    {
        return StudentFactory::new();
    }
}