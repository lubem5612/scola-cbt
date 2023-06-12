<?php


namespace Transave\ScolaCbt\database\migrations;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('questions')->cascadeOnDelete();
            $table->enum('is_correct_option', ['yes', 'no'])->default('on')->index();
            $table->string('content')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}