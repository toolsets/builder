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

    public static $migrated = false;


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
                    if(!isset($existingColumns[$col_name]['attributes']))
                    {
                        $existingColumns[$col_name]['attributes']=[];
                    }

                    $current_attr = $existingColumns[$col_name]['attributes'];
                    $attributes = $column->toArray();
                    unset($attributes['change']);
                    $existingColumns[$col_name]['attributes'] = array_merge($current_attr, $attributes);
                    $existingColumns[$col_name]['migrated'] = static::$migrated;
                }
            }

            $this->data[$connectionName][$databaseName][$table]['columns'] = $existingColumns;
        }


        if (!empty($addedColumns)) {
            foreach ($addedColumns as $column) {
                $col_name = $column->name;

                if (!isset($existingColumns[$col_name])) {
                    if(!isset($existingColumns[$col_name]['attributes']))
                    {
                        $existingColumns[$col_name]['attributes']=[];
                    }

                    $existingColumns[$col_name]['attributes'] =  $column->toArray();
                    $existingColumns[$col_name]['migrated'] = static::$migrated;
                }
            }

            $this->data[$connectionName][$databaseName][$table]['columns'] = $existingColumns;
        }

        $commands = $blueprint->getCommands();

        if(!empty($commands))
        {
            $table_state = $this->data[$connectionName][$databaseName][$table];
            $newState = $this->processCommands($commands, $table_state);
            $this->data[$connectionName][$databaseName][$table] = $newState;
        }
//        $this->data[$connectionName][$databaseName][$table]['command'] = $commands;
        $this->data[$connectionName][$databaseName][$table]['migrated'] = static::$migrated;

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
                        'commands' => $describer['commands']
                    ];
                }

                $dbs[$database] = [
                    'tables' => $tbls
                ];
            }


            $formatted['connections'][$connection] = [
                'databases' => $dbs
            ];
        }

        return $formatted;
    }

    protected function processCommands($commands, $table_state)
    {
        $columns = $table_state['columns'];

        if(!isset($table_state['commands']))
        {
            $table_state['commands'] = [];
        }

        foreach($commands as $command)
        {
            $command_array = $command->toArray();
            $command_array['migrated'] = static::$migrated;
            $table_state['commands'][] = $command_array;
        }

        return $table_state;
    }
}