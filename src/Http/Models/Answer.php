<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\CourseFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Answer extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "answers";
    protected $guarded = [
        "id"
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(config('scola-cbt.auth_model'));
    }

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option() : BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    protected static function newFactory()
    {
        return CourseFactory::new();
    }
}