<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 15/12/2016
 * Time: 7:57 PM
 */

namespace Toolkits\LaravelBuilder;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Toolkits\LaravelBuilder\Console\MakeCommand;
use Toolkits\LaravelBuilder\Services\Database\Connection;
use Toolkits\LaravelBuilder\Services\Database\DbConnectionProvider;
use Toolkits\LaravelBuilder\Services\Database\Migration\Console\BlueprintWatcher;
use Toolkits\LaravelBuilder\Services\Database\Migration\Console\MigrationReader;

class LaravelBuilderServiceProvider extends ServiceProvider
{

    protected $package_name = 'tlb';

    protected $config_name = 'builder';

    protected $namespace = 'Toolkits\LaravelBuilder\Controllers';


    public function boot()
    {

        define('_TLB_PKG_NAME_', $this->package_name);
        require __DIR__ . '/helpers.php';


        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->package_name);
        $this->builderRoutes();


        $this->publishes([
            __DIR__.'/../build' => public_path('vendor/' . $this->package_name),
            __DIR__.'/../resources/assets/fonts' => public_path('vendor/' . $this->package_name . '/fonts')
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCommand::class
            ]);
        }



        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package_name);
    }


    public function register()
    {
       $this->app->register(new DbConnectionProvider($this->app));

        $this->registerMigrator();
    }



    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {

        $this->app->singleton('command.migration_reader', function ($app) {

            $migrator = $app->make('migrator');
            $connection = $app->make('db.connection');
            return new MigrationReader($migrator, $connection);
        });

        $this->commands([
            'command.migration_reader'
        ]);
    }



    protected function builderRoutes()
    {
        $routePrefix = $this->app['config']->get($this->config_name . '.route.prefix', 'builder');
        Route::group([
            'middleware' => 'web',
            'prefix' => $routePrefix,
            'namespace' => $this->namespace
        ], function ($router) {
            require __DIR__ . '/routes/web.php';
        });
    }
}
