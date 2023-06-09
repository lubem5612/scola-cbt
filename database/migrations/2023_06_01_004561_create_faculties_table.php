<?php


namespace Transave\ScolaCbt\database\migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultiesTable
{
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('faculties');
    }
}
