<?php

namespace Transave\ScolaCbt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Manager extends Model
{
    use HasFactory, UUIDHelper;

    protected $guarded = [
        "id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}