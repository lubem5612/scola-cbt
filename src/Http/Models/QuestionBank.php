<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Database\Factories\QuestionBankFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class QuestionBank extends Model
{
    use UUIDHelper, HasFactory;
    protected $table = 'cbt_question_banks';
    protected $guarded = [ 'id' ];
    
    public function session()
    {
        return $this->belongsTo(Session::class);
    }
    
    protected static function newFactory()
    {
        return QuestionBankFactory::new();
    }
}