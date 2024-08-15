<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('faculty_id')->constrained('cbt_faculties')->cascadeOnDelete();
            $table->string('name', 80)->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_departments');
    }
};
