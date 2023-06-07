<?php


namespace Transave\ScolaCbt\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class Student extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = "students";

    protected $guarded = [
        "id"
    ];
}