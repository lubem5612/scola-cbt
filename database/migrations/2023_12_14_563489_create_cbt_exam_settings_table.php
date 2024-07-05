<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('cbt_exam_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('exam_id')->constrained('cbt_exams')->cascadeOnDelete();
            $table->boolean('show_max_scores')->default(false);
            $table->boolean('display_question_randomly')->default(true);
            $table->boolean('allow_multiple_attempts')->default(true);
            $table->boolean('is_public_access')->default(false);
            $table->tinyInteger('browser_warn_level')->default(0)->comment('0-disable, 1-warning, 2-warning and blocking');
            $table->text('farewell_message')->nullable();
            $table->boolean('unordered_answering')->nullable(true);
            $table->boolean('set_pass_mark')->nullable(true);
            $table->float('pass_mark_value', 5, 2)->nullable(0);
            $table->string('pass_mark_unit', 150)->nullable()->comment('percent, points');
            $table->boolean('grade_with_points')->default(false);
            $table->boolean('send_result_mail')->default(false);
            $table->boolean('send_congratulatory_mail')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cbt_exam_settings');
    }
}