<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\ExaminerFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Examiner extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "cbt_examiners";

    protected $guarded = [
        "id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department() : BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    protected static function newFactory()
    {
        return ExaminerFactory::new();
    }
}