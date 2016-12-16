<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 15/12/2016
 * Time: 7:57 PM
 */

namespace Toolkits\LaravelBuilder;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Toolkits\LaravelBuilder\Console\MakeCommand;

class LaravelBuilderServiceProvider extends ServiceProvider
{

    protected $package_name = 'tlb';

    protected $config_name = 'builder';

    protected $namespace = 'Toolkits\LaravelBuilder\Controllers';


    public function boot()
    {
        define('_TLB_PKG_NAME_', $this->package_name);
        require __DIR__ . '/helpers.php';

//        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', $this->package_name);
        $this->builderRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../build' => public_path('vendor/' . $this->package_name),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package_name);
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
