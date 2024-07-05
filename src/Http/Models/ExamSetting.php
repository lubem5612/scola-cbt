<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\ExamSettingFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class ExamSetting extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "cbt_exam_settings";

    protected $guarded = [
        "id"
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    protected static function newFactory()
    {
        return ExamSettingFactory::new();
    }
}