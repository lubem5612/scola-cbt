<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\ExamDepartmentFactory;

class ExamDepartment extends Model
{
    use HasFactory;
    protected $table = "cbt_exam_departments";
    protected $hidden = ['created_at', 'updated_at'];
    protected $guarded = [ 'id' ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected static function newFactory()
    {
        return ExamDepartmentFactory::new();
    }
}