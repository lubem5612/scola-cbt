<?php

    namespace Transave\ScolaCbt\database\migrations;


    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateManagersTable
    {
        public function up()
        {
            Schema::create('managers', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

                $table->timestamps();
            });
        }

        public function down()
        {
            Schema::dropIfExists('managers');
        }
    }

