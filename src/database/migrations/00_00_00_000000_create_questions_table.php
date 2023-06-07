<?php


namespace Transave\ScolaCbt\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('question_type');
            $table->integer('unit_score');
            $table->text('question');
            $table->string('images')->nullable();
            $table->json('answers');
            $table->foreignId('exam_id')->constrained('exams');
            $table->timestamps();

            $table->index(['subject_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
