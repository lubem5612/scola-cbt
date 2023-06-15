<?php


namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Transave\ScolaCbt\Database\Factories\UserFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUIDHelper;
    protected $table = "users";

    protected $fillable = [
        "id"
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}