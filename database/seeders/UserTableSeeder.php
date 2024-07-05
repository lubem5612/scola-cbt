<?php

namespace Transave\ScolaCbt\Database\Seeders;


use Carbon\Carbon;
use Transave\ScolaCbt\Http\Models\User;

class UserTableSeeder
{
    public function run()
    {
        if (User::query()->count() == 0) {
            foreach ($this->users as $index => $user)
            {
                $user['email_verified_at'] = Carbon::now();
                $user['password'] = bcrypt('password');
                User::query()->create($user);
            }
        }
    }

    protected $users = [
        0 => [
            'email' => 'admin@scolacbt.com',
            'telephone' => '+23480123456789',
            'first_name' => 'Admin',
            'last_name' => 'CBT-Platform',
            'role' => 'admin',
            'is_verified' => 1,
        ]
    ];
}