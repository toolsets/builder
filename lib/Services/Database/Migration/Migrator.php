<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 10:57 AM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration;


use Illuminate\Database\Migrations\Migrator as LaravelMigrator;
use Illuminate\Support\Collection;

class Migrator extends LaravelMigrator
{

    /**
     * Run the outstanding migrations at a given path.
     *
     * @param  array|string  $paths
     * @param  array  $options
     * @return array
     */
    public function run($paths = [], array $options = [])
    {
        $this->notes = [];

        $files = $this->getMigrationFiles($paths);

//        // Once we grab all of the migration files for the path, we will compare them
//        // against the migrations that have already been run for this package then
//        // run each of the outstanding migrations against a database connection.
        $ran = $this->repository->getRan();

        $migrations_ran = Collection::make($files)
            ->reject(function($file) use ($ran)
            {
                return !in_array($this->getMigrationName($file), $ran);
            })->values()->all();;

        MigrationSnapshot::$migrated = true;
        $this->requireFiles($migrations_ran);
        $this->runMigrationList($migrations_ran, $options);
        MigrationSnapshot::$migrated = false;

//
        $migrations = Collection::make($files)
            ->reject(function ($file) use ($ran) {
                return in_array($this->getMigrationName($file), $ran);
            })->values()->all();


        $this->requireFiles($migrations);

        $this->runMigrationList($migrations, $options);

        return $migrations;
    }
}