<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateExamsTable extends Migration
{
    public function up()
    {
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users');
            $table->foreignUuid('course_id')->constrained('cbt_courses')->cascadeOnDelete();
            $table->foreignUuid('session_id')->constrained('cbt_sessions')->cascadeOnDelete();
            $table->string('semester', 20);
            $table->string('level', 20);
            $table->string('exam_name', 200);
            $table->float('max_score_obtainable', 6, 2)->default(0);
            $table->string('exam_mode', 50)->index();
            $table->time('start_time')->nullable();
            $table->integer('duration')->nullable();
            $table->enum('unit_of_time', ['minute', 'hour'])->nullable();
            $table->dateTime('exam_date')->nullable();
            $table->text('instruction');
            $table->string('venue')->index();
            $table->timestamps();

            $table->index(['semester', 'level']);
            $table->index(['exam_name']);
            $table->index(['max_score_obtainable']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_exams');
    }
}