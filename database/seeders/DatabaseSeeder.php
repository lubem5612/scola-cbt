<?php

namespace Transave\ScolaCbt\Database\Seeders;

class DatabaseSeeder
{
    public function definition()
    {
        return [
            'users' => UserTableSeeder::class,
        ];
    }
}