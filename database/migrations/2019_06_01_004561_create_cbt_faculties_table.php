<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_faculties', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_faculties');
    }
};