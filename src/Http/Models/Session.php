<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\SessionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Session extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'cbt_sessions';

    protected $guarded = [
        "id"
    ];


    protected static function newFactory()
    {
        return SessionFactory::new();
    }
}