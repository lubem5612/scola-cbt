<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminersTable extends Migration
{
    public function up()
    {
        Schema::create('cbt_examiners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('cbt_departments')->cascadeOnDelete();
            $table->string('phone', 20)->nullable()->index();
            $table->string('photo', 700)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_examiners');
    }
}
