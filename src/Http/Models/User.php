<?php


namespace Transave\ScolaCbt\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Transave\ScolaCbt\Database\Factories\UserFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class User extends Authenticatable
{
    use HasFactory, Notifiable, UUIDHelper, HasApiTokens;
    protected $table = "users";

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
        'details'
    ];

    public function staff() : HasOne
    {
        return $this->hasOne(Staff::class)->with(['department', 'department.faculty']);
    }

    public function examiner() : HasOne
    {
        return $this->hasOne(Examiner::class)->with(['department', 'department.faculty']);
    }

    public function manager() : HasOne
    {
        return $this->hasOne(Manager::class);
    }

    public function student() : HasOne
    {
        return $this->hasOne(Student::class)->with(['department', 'department.faculty']);
    }

    public function getDetailsAttribute()
    {
        switch ($this->role) {
            case "staff":
                return $this->staff()->first();
            case "examiner":
                return $this->examiner()->first();
            case "manager":
                return $this->manager()->first();
            case "student":
                return $this->student()->first();
            default:
                return null;
        }
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}