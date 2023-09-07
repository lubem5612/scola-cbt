<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('questions')->cascadeOnDelete();
            $table->enum('is_correct_option', ['yes', 'no'])->default('no')->index();
            $table->string('content', 700)->index();
            $table->string('file', 700)->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}
