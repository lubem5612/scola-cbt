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
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->foreignUuid('user_id')->after('id')->nullable()->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('course_id')->after('department_id')->nullable()->constrained('cbt_courses')->cascadeOnDelete();
            $table->string('level')->after('course_id')->nullable()->index();
            
            $table->dropForeign(['exam_id']);
            $table->dropColumn('exam_id');
        });
        
        Schema::create('cbt_exam_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exam_id')->constrained('cbt_exams')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('cbt_questions')->cascadeOnDelete();
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
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['course_id']);
            $table->dropIndex(['level']);
            
            $table->dropColumn('user_id');
            $table->dropColumn('course_id');
            $table->dropColumn('level');
            
            $table->foreignUuid('exam_id')->nullable()->constrained('cbt_exams')->cascadeOnDelete();
        });
    
        Schema::dropIfExists('cbt_exam_questions');
    }
};