<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends  Migration
{
    public function up()
    {
        Schema::create('cbt_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('department_id')->constrained('cbt_departments')->cascadeOnDelete();
            $table->string('name', 100)->index();
            $table->string('code',11)->nullable()->index();
            $table->integer('credit_load')->default(1)->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_courses');
    }
};
