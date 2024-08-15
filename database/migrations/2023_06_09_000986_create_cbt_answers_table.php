<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('cbt_questions')->cascadeOnDelete();
            $table->foreignUuid('option_id')->nullable()->constrained('cbt_options')->cascadeOnDelete();
            $table->integer('attempts')->default(1);
            $table->text('content')->nullable();
            $table->string('file', 700)->nullable();
            $table->float('score', 5,2)->index()->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_answers');
    }
};