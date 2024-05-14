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
        Schema::table('student_exams', function (Blueprint $table) {
            $table->enum('status', ['ongoing', 'completed', 'terminated'])->index()->after('attempts')->default('ongoing');
            $table->timestamp('start_time')->after('status')->useCurrent();
            $table->timestamp('end_time')->after('start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_exams', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('status');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
};