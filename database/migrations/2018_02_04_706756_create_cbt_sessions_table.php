<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->enum('is_active', ['yes', 'no'])->default('no')->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_sessions');
    }
};