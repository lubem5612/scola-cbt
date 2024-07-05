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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'examiner', 'staff', 'student']);
            $table->timestamps();

            $table->index(['first_name']);
            $table->index(['last_name']);
            $table->index(['is_verified']);
            $table->index(['role']);
        });

        \Illuminate\Support\Facades\DB::table('users')
            ->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'first_name' => 'Scola-CBT',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_verified' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
};