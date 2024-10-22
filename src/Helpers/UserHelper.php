<?php


namespace Transave\ScolaCbt\Helpers;


use Illuminate\Database\Eloquent\Relations\HasOne;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\Staff;
use Transave\ScolaCbt\Http\Models\Student;

trait UserHelper
{
    /**
     * relationships imported from the package user model
     * @return HasOne
     */
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
    
    public function getPhoneAttribute()
    {
        return $this->telephone;
    }
}