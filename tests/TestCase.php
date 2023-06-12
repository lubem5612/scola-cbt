<?php


namespace Transave\ScolaCbt\Tests;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Transave\ScolaCbt\ScolaCbtServiceProvider;

class TestCase extends BaseTestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->app->make(EloquentFactory::class)->load($this->baseDir().DIRECTORY_SEPARATOR.'factories');

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
        $this->loadMigrationsFrom($this->baseDir() . '/migrations');
    }

    private function baseDir(){
        return str_replace('tests','database',__DIR__);
    }

}