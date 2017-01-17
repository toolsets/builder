<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 10:57 AM
 */

namespace Toolsets\LaravelBuilder\Services\Database\Migration;


use Illuminate\Database\Migrations\Migrator as LaravelMigrator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class Migrator extends LaravelMigrator
{
    public $is_toolset = true;

    /**
     * Run the outstanding migrations at a given path.
     *
     * @param  array|string  $paths
     * @param  array  $options
     * @return array
     */
    public function runSnapshot($paths = [], array $options = [])
    {
        $this->notes = [];

        $files = $this->getMigrationFiles($paths);

        $ran = $this->repository->getRan();

        $migrations_ran = Collection::make($files)
            ->reject(function($file) use ($ran)
            {
                return !in_array($this->getMigrationName($file), $ran);
            })->values()->all();;

        MigrationSnapshot::$migrated = true;

        $this->requireFiles($migrations_ran);
        $this->runPending($migrations_ran, $options);

        MigrationSnapshot::$migrated = false;

        $migrations = $this->pendingMigrations($files, $ran);

        $this->requireFiles($migrations);
        $this->runPending($migrations, $options);

        return $migrations;
    }


    public function run($paths = [], array $options = [])
    {
        $migrations =  parent::run($paths, $options);

        Artisan::call('builder:read-migrations');

        return $migrations;
    }


    public function rollback($paths = [], array $options = [])
    {
        $migrations =  parent::rollback($paths, $options);

        Artisan::call('builder:read-migrations');

        return $migrations;
    }


    public function reset($paths = [], $pretend = false)
    {
        $migrations =  parent::reset($paths, $pretend);

        Artisan::call('builder:read-migrations');

        return $migrations;
    }
}