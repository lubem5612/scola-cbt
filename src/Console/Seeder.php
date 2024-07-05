<?php


namespace Transave\ScolaCbt\Console;


use Illuminate\Console\Command;
use Transave\ScolaCbt\Database\Seeders\DatabaseSeeder;

class Seeder extends Command
{
    protected $signature = 'cbt:seed';
    protected $description = 'seed package data to tables';

    public function handle()
    {
        $seeders = (new DatabaseSeeder())->definition();
        foreach ($seeders as $index => $seeder) {
            $this->info('seeding '.$index.' begins');
            $seed = new $seeder();
            $seed->run();
            $this->info($index.' seeded successfully');
        }
    }
}