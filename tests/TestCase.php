<?php


namespace Transave\ScolaCbt\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Transave\ScolaCbt\ScolaCbtServiceProvider;

class TestCase extends BaseTestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
//        Artisan::call('passport:install');
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ScolaCbtServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'../database/migrations');
    }

}