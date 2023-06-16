<?php

namespace Transave\ScolaCbt;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Transave\ScolaCbt\Helpers\PublishMigrations;
use Transave\ScolaCbt\Http\Models\User;

class ScolaCbtServiceProvider extends ServiceProvider
{
    use PublishMigrations;
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'transave');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'transave');
         $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
         $this->registerRoutes();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Config::set('auth.defaults', [
            'guard' => 'api',
            'passwords' => 'users',
        ]);

        Config::set('auth.guards.api', [
            'driver' => 'session',
            'provider' => 'users',
            'hash' => false,
        ]);

        Config::set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/scola-cbt.php', 'scola-cbt');

        // Register the service the package provides.
        $this->app->singleton('scola-cbt', function ($app) {
            return new ScolaCbt;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['scola-cbt'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/scola-cbt.php' => config_path('scola-cbt.php'),
        ], 'cbt-config');

        // Publishing migrations
        $this->registerMigrations(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'cbt-migrations');
        
        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/transave'),
        ], 'scola-cbt.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/transave'),
        ], 'scola-cbt.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/transave'),
        ], 'scola-cbt.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function routeConfiguration()
    {
        return [
            'prefix' => config('scola-cbt.route.prefix'),
            'middleware' => config('scola-cbt.route.middleware'),
        ];
    }


}
