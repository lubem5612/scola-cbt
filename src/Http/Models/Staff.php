<?php

namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\StaffFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Staff extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'staff';

    protected $guarded = [
        "id"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    protected static function newFactory()
    {
        return StaffFactory::new();
    }

}