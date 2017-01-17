<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 7:24 PM
 */

namespace Toolsets\LaravelBuilder\Repositories;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Toolsets\LaravelBuilder\Parsers\Yaml;
use Toolsets\LaravelBuilder\Services\Database\Migration\MigrationCreator;

class DatabaseTables
{

    protected $tbl_column_fields = [
        'name' => [
            'label' => 'Name',
            'type' => 'text',
            'validation' => 'required|regex:^[0-9_a-z]+$'
        ],
        'type' => [
            'label' => 'Type',
            'type' => 'select',
            'options' => [
                'integer' => 'Integer',
                'string' => 'String',
                'text' => 'Text',
                'smallInteger' => 'Small Integer',
                'bigInteger' => 'Big Integer',
                'date' => 'Date',
                'time' => 'Time',
                'dateTime' => 'Date and Time',
                'timestamp' => 'Timestamp',
                'binary' => 'Binary',
                'boolean' => 'Boolean',
                'decimal' => 'Decimal',
                'double' => 'Double'
            ],
            'validation' => 'required'
        ],
        'length' => [
            'label' => 'Length',
            'type' => 'text',
            'validation' => 'regex:(^[0-9]+$)|(^[0-9]+,[0-9]+$)'
        ],
        'unsigned' => [
            'label' => 'Unsigned',
            'type' => 'checkbox',
            'validation' => false
        ],
        'allow_null' => [
            'label' => 'Allow NULL',
            'type' => 'checkbox',
            'validation' => false
        ],
        'auto_increment' => [
            'label' => 'Auto Increment',
            'type' => 'checkbox',
            'validation' => false
        ],
        'primary_key' => [
            'label' => 'Primary Key',
            'type' => 'checkbox',
            'validation' => false
        ],
        'default' => [
            'label' => 'Default',
            'type' => 'text',
            'validation' => false
        ]

    ];


    protected $tbl_fields = [
        'name' => [
            'type' => 'text',
            'validation' => 'required|regex:^[0-9_a-z]+$'
        ],
        'connection' => [
            'type' => 'select',
            'options' => ['default']
        ]
    ];


    protected $serials;


    public function getTables()
    {
        $schema_file = tbl_project_path('database/db_schema.yml');
        if (!Storage::disk('local')->exists($schema_file)) {
            tbl_update_snapshot();
        }

        $db_connection = config('database.default');
        $database_name = config('database.connections.'.$db_connection . '.database');

        $content = Storage::disk('local')->get($schema_file);

        $schema = Yaml::make()->parse($content);

        $tables = [];
        $tables_list = [];

        if (isset($schema['connections'][$db_connection])
            && isset($schema['connections'][$db_connection]['databases'][$database_name])) {
            $tables = $this->normalizeTableData($schema['connections'][$db_connection]['databases'][$database_name]);
            foreach ($tables as $table) {
                $tables_list[] = $table['table_name'];
            }
        }

        return [
            'connection' => $db_connection,
            'database' => $database_name,
            'tables' => $tables,
            'tables_list' => $tables_list
        ];
    }

    protected function normalizeTableData($tables)
    {
        return array_values($tables['tables']);
    }

    public function createNewTable($data)
    {
        //create table definitions
        $definitions = $this->makeTableDefinitions($data);

        $tableName = $this->getNewTableClassName($definitions['name']);

        /**
         * @var $creator MigrationCreator
         */
        $creator = app('toolsets.migration.creator');

        $path = $this->getMigrationPath();

        $creator->create($tableName, $path, $definitions, true);

        // run database snapshot update
        tbl_update_snapshot();
    }


    protected function getNewTableClassName($tableName)
    {
        $tableName = Str::studly($tableName);
        return 'TBK_CREATE_TABLE_' . $tableName;
    }


    protected function getMigrationPath()
    {
        return tbl_migration_path();
    }


    protected function makeTableDefinitions($data, $create = true)
    {
        $table_name = Str::snake($data['name']);
        $definitions = [];
        $definitions['name'] = $table_name;
        $definitions['UP'] = [
            'columns' => $data['columns'],
            'relations' => $data['relations'],
            'indexes' => $data['indexes']
        ];

        if ($create != true) {
            $definitions['DOWN'] = [
                'columns' => $data['columns'],
                'relations' => $data['relations'],
                'indexes' => $data['indexes']
            ];
        }

        return $definitions;
    }


}