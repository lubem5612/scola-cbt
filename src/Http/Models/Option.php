<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\OptionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Option extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'options';

    protected $guarded = [
        "id"
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }


    protected static function newFactory()
    {
        return OptionFactory::new();
    }

}