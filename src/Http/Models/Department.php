<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaCbt\Database\Factories\DepartmentFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Department extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'cbt_departments';

    protected $guarded = [
        "id"
    ];

    protected $appends = [ 'faculty_name' ];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function questions() : HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function exams() : BelongsToMany
    {
        return $this->BelongsToMany(Exam::class, 'cbt_exam_departments', 'department_id', 'exam_id');
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function getFacultyNameAttribute()
    {
        return $this->faculty()->first()?->name;
    }

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }
}