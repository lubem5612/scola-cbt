<?php


namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaCbt\Database\Factories\UserFactory;
use Transave\ScolaCbt\Helpers\UserHelper;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    
    use UserHelper;
    
    protected $table = "fc_users";
    
    protected $guarded = [
        "id"
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
        'is_verified',
        'email_verified_at'
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    protected $appends = [
        'details',
        'phone'
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}