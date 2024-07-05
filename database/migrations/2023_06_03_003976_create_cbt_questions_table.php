<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends  Migration
{
    public function up()
    {
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exam_id')->nullable()->constrained('cbt_exams')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('cbt_departments')->cascadeOnDelete();
            $table->string('question_type', 50);
            $table->float('score_obtainable', 6, 2)->default(0)->index();
            $table->text('question');
            $table->string('file', 700)->nullable();
            $table->string('answers', 700)->nullable();
            $table->timestamps();

            $table->index(['question_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_questions');
    }
};
