<?php

namespace Transave\ScolaCbt;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Transave\ScolaCbt\Console\ExamDepartmentSeeder;
use Transave\ScolaCbt\Console\Seeder;
use Transave\ScolaCbt\Helpers\PublishMigrations;
use Transave\ScolaCbt\Http\Middlewares\AllowIfAdmin;
use Transave\ScolaCbt\Http\Middlewares\AllowIfExaminer;
use Transave\ScolaCbt\Http\Middlewares\AllowIfManager;
use Transave\ScolaCbt\Http\Middlewares\AllowIfStaff;
use Transave\ScolaCbt\Http\Middlewares\AllowIfStudent;
use Transave\ScolaCbt\Http\Middlewares\VerifiedAccount;

class ScolaCbtServiceProvider extends ServiceProvider
{
    use PublishMigrations;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'transave');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'cbt');
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
            'model' => \config('scola-cbt.auth_model',  App\Models\User::class),
        ]);

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('admin', AllowIfAdmin::class);
        $router->aliasMiddleware('verify', VerifiedAccount::class);
        $router->aliasMiddleware('staff', AllowIfStaff::class);
        $router->aliasMiddleware('examiner', AllowIfExaminer::class);
        $router->aliasMiddleware('student', AllowIfStudent::class);
        $router->aliasMiddleware('manager', AllowIfManager::class);

        Config::set('filesystems.disks.azure', [
            'driver'            => 'azure',
            'local_address'     => env('AZURE_STORAGE_LOCAL_ADDRESS', 'local'),
            'name'              => env('AZURE_STORAGE_NAME', 'raadaastorage'),
            'key'               => env('AZURE_STORAGE_KEY', ""),
            'container'         => env('AZURE_STORAGE_CONTAINER', "raadaatesting"),
            'prefix'            => env('AZURE_STORAGE_PREFIX', "scola-cbt"),
            'url'               => env('AZURE_STORAGE_URL', null),
        ]);
        
        Config::set('filesystems.disks.s3', [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
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
        $this->mergeConfigFrom(__DIR__.'/../config/endpoints.php', 'endpoints');

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
//        $this->registerMigrations(__DIR__ . '/../database/migrations');
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'cbt-migrations');
        
        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/cbt'),
        ], 'cbt-views');

        // Publishing assets.
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/scola-cbt'),
        ], 'cbt-assets');

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/transave'),
        ], 'scola-cbt.views');*/

        // Registering package commands.
        $this->commands([
            ExamDepartmentSeeder::class,
            Seeder::class,
        ]);
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
