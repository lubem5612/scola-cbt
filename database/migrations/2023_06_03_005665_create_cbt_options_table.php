<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_options', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id')->constrained('cbt_questions')->cascadeOnDelete();
            $table->enum('is_correct_option', ['yes', 'no'])->default('no')->index();
            $table->string('content', 700)->nullable()->index();
            $table->string('file', 700)->nullable()->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_options');
    }
};
