<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('cbt_student_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->constrained('cbt_students')->cascadeOnDelete();
            $table->foreignUuid('exam_id')->constrained('cbt_exams')->cascadeOnDelete();
            $table->integer('attempts')->default(1);
            $table->timestamps();


        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cbt_student_exams');
    }
};