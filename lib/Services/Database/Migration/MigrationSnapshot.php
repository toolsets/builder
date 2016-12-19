<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 9:54 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration;


use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Storage;
use Toolkits\LaravelBuilder\Parsers\Yaml;


class MigrationSnapshot
{

    public static $shots = null;


    protected $data = [];

    public static function capture(Blueprint $blueprint, Connection $connection)
    {
        if(is_null(static::$shots)) {
            static::$shots = new static();
        }

        static::$shots->record($blueprint, $connection);
    }


    public function record(Blueprint $blueprint, Connection $connection)
    {
        $connectionName = $connection->getName();
        $dbConfig = config('database.connections.'.$connectionName);
        $databaseName = $dbConfig['database'];

        $table = $blueprint->getTable();
        $addedColumns = $blueprint->getAddedColumns();
        $changedColumns = $blueprint->getChangedColumns();
        if (!isset($this->data[$connectionName])) {
            $this->data[$connectionName] = [];
        }

        if (!isset($this->data[$connectionName][$databaseName])) {
            $this->data[$connectionName][$databaseName] = [];
        }

        if (!isset($this->data[$connectionName][$databaseName][$table])) {
            $this->data[$connectionName][$databaseName][$table] = ['columns' => []];
        }

        $existingColumns = $this->data[$connectionName][$databaseName][$table]['columns'];

        if (!empty($changedColumns)) {
            foreach ($changedColumns as $column) {
                $col_name = $column->name;

                if (isset($existingColumns[$col_name])) {
                    $current = $existingColumns[$col_name];
                    $attributes = $column->toArray();
                    unset($attributes['change']);
                    $existingColumns[$col_name] = array_merge($current, $attributes);
                }
            }

            $this->data[$connectionName][$databaseName][$table]['columns'] = $existingColumns;
        }


        if (!empty($addedColumns)) {
            foreach ($addedColumns as $column) {
                $col_name = $column->name;

                if (!isset($existingColumns[$col_name])) {
                    $existingColumns[$col_name] =  $column->toArray();
                }
            }

            $this->data[$connectionName][$databaseName][$table]['columns'] = $existingColumns;
        }

        $commands = $blueprint->getCommands();
        $this->data[$connectionName][$databaseName][$table]['command'] = $commands;

    }


    public function get()
    {
        return $this->transform($this->data);
    }

    public function save()
    {
        $parser = Yaml::make();
        $yaml = $parser->toString($this->transform($this->data));

        $project_path =  'toolsets/builder/' . 'database' ;
        $project_real_path = storage_path($project_path);

        if (!file_exists($project_real_path)) {
            Storage::makeDirectory($project_path);
        }

        $filepath = $project_path . '/db_schema.yml';

        Storage::put($filepath, $yaml);

        return storage_path($filepath);
    }


    public function transform($data)
    {
        $formatted = [];

        foreach ($data as $connection => $databases) {
            $dbs = [];

            foreach ($databases as $database => $tables) {
                $tbls = [];

                foreach ($tables as $table => $describer) {
                    $tbls[] = [
                        'table_name' => $table,
                        'columns' => $describer['columns'],
                        'command' => $describer['command']
                    ];
                }

                $dbs[] = [
                    'db_name' => $database,
                    'tables' => $tbls
                ];
            }


            $formatted['connections'][] = [
                'connection' => $connection,
                'databases' => $dbs
            ];
        }

        return $formatted;
    }
}