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
        Schema::create('cbt_exam_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('exam_id')->nullable()->constrained('cbt_exams')->cascadeOnDelete();
            $table->foreignUuid('department_id')->constrained('cbt_departments')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbt_exam_departments');
    }
};