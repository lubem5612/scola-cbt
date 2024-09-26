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
        Schema::table('cbt_exam_settings', function (Blueprint $table) {
            $table->boolean('show_student_result')->after('grade_with_points')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cbt_exam_settings', function (Blueprint $table) {
            $table->dropColumn('show_student_result');
        });
    }
};
