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
        Schema::create('cbt_question_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 200)->index();
            $table->string('description', 766)->nullable();
            $table->string('level', 30)->nullable();
            $table->foreignUuid('session_id')->nullable()->constrained('cbt_sessions');
            
            $table->timestamps();
        });
        
        Schema::table('cbt_questions', function (Blueprint $table) {
            $table->integer('difficulty_level')->after('question')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cbt_question_banks');
        
        Schema::table('cbt_exam_settings', function (Blueprint $table) {
            $table->dropColumn('show_student_result');
        });
    }
};

