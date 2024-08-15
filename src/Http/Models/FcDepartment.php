<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class FcDepartment extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'fc_departments';

    protected $guarded = [
        "id"
    ];

}