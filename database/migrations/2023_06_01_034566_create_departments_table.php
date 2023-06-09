<?php


namespace Transave\ScolaCbt\database\migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable
{
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUlid('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $table->string('name')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
