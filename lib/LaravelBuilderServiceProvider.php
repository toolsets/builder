<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 15/12/2016
 * Time: 7:57 PM
 */

namespace Toolsets\LaravelBuilder;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Toolsets\LaravelBuilder\Console\MakeCommand;
use Toolsets\LaravelBuilder\Services\Database\Connection;
use Toolsets\LaravelBuilder\Services\Database\DbConnectionProvider;
use Toolsets\LaravelBuilder\Services\Database\Migration\Console\BlueprintWatcher;
use Toolsets\LaravelBuilder\Services\Database\Migration\Console\MigrationReader;
use Toolsets\LaravelBuilder\Services\Database\Migration\MigrationCreator;
use Toolsets\LaravelBuilder\Services\Database\Migration\Migrator;

class LaravelBuilderServiceProvider extends ServiceProvider
{

    protected $package_name = 'tlb';

    protected $config_name = 'builder';

    protected $namespace = 'Toolsets\LaravelBuilder\Controllers';


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

        $this->registerMigrationReaderCommand();
    }



    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrationReaderCommand()
    {
        $this->registerMigrator();

        $this->app->bind('toolsets.migration.creator', MigrationCreator::class);

        $this->app->when(MigrationReader::class)
            ->needs('Illuminate\Database\Migrations\Migrator')
            ->give(function ($app) {
                $repository = $app['migration.repository'];
                return new Migrator($repository, $app['db'], $app['files']);
            });


        $this->app->bind('command.migration_reader', MigrationReader::class);

        $this->commands([
            'command.migration_reader'
        ]);
    }


    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.

        $this->app->afterResolving('migration.repository', function($repo){
            $this->app->singleton('migrator', function ($app) use($repo) {
                return new Migrator($repo, $app['db'], $app['files']);
            });
        });


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
