<?php


namespace Transave\ScolaCbt\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateExamsTable extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('exam_type');
            $table->date('session');
            $table->string('semester');
            $table->integer('total_score');
            $table->string('faculty');
            $table->string('department');
            $table->string('level');
            $table->string('exam_mode');
            $table->string('answer_type');
            $table->time('duration');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('instruction');
            $table->string('venue');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
}