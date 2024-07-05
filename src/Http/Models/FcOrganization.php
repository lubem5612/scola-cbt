<?php


namespace Transave\ScolaCbt\Http\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Transave\ScolaCbt\Helpers\UUIDHelper;

class FcOrganization extends Model
{
    use HasFactory, UUIDHelper;

    protected $table = 'fc_organizations';

    protected $guarded = [
        "id"
    ];

}