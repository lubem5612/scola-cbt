<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fc_users', function (Blueprint $table) {
            $table->string('role', 40)->after('job_title')->nullable();
            $table->binary('profile_image')->nullable()->change();
            $table->boolean('is_verified')->after('role')->default(false);
            $table->timestamp('email_verified_at')->after('is_verified')->nullable();
        });

        Schema::dropIfExists('users');
    }

    public function down()
    {
        Schema::table('fc_users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('is_verified');
            $table->dropColumn('email_verified_at');
        });
    }
};