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
    public function up() {
        Schema::create('cbt_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('fc_users')->cascadeOnDelete();
            $table->foreignUuid('department_id')->nullable()->constrained('cbt_departments')->cascadeOnDelete();
            $table->string('registration_number', 50)->nullable();
            $table->string('photo', 700)->nullable();
            $table->string('current_level', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('address', 700)->nullable();
            $table->timestamps();

            $table->index(['phone', 'address']);
            $table->index(['registration_number']);
            $table->index(['current_level']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('cbt_students');
    }
};