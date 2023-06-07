<?php


namespace Transave\ScolaCbt\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Option extends Model
{
    use HasFactory, UUIDHelper;

    protected $guarded = [
        "id"
    ];

    public function question()
    {
        $this->belongsTo(Question::class);
    }

}