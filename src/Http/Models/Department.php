<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Transave\ScolaCbt\Database\Factories\DepartmentFactory;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Department extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'departments';

    protected $guarded = [
        "id"
    ];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    protected static function newFactory()
    {
        return DepartmentFactory::new();
    }
}