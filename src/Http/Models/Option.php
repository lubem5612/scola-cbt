<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Transave\ScolaCbt\Database\Factories\OptionFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Option extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'cbt_options';

    protected $guarded = [
        "id"
    ];

    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }

    protected static function newFactory()
    {
        return OptionFactory::new();
    }

}