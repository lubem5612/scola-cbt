<?php


namespace Transave\ScolaCbt\database\migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->integer('credit_load')->default(1)->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
