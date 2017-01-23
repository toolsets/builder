<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 7:24 PM
 */

namespace Toolsets\Builder\Repositories;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Toolsets\Builder\Parsers\Yaml;
use Toolsets\Builder\Services\Database\Migration\MigrationCreator;

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
        }

        return [
            'connection' => $db_connection,
            'database' => $database_name,
            'tables' => $tables['tables'],
            'tables_list' => $tables['tables_list']
        ];
    }

    protected function normalizeTableData($tables)
    {
        $tables = array_values($tables['tables']);
        $normalized = [
            'tables' => [],
            'tables_list' => []
        ];
        foreach($tables as $table) {

            $normalized['tables_list'][] = $table['table_name'];

            $relations = [];

            if(isset($table['relations'])) {
                foreach($table['relations'] as $relationIndex => $relation) {
                    $relations[] = [
                        'column' => $relation['columns'][0],
                        'index' => $relation['index'],
                        'fk_column' => $relation['fk_column'],
                        'fk_table' => $relation['fk_table'],
                        'on_delete' => isset($relation['on_delete']) ? $relation['on_delete'] : null,
                        'on_update' => isset($relation['on_update']) ? $relation['on_update'] : null,
                        'migrated' => $relation['migrated']
                    ];
                }
            }

            $indexes = [];

            if(isset($table['indexes'])) {
                foreach($table['indexes'] as $indexKey => $index) {
                    $indexes[] = $index;
                }
            }

            $table['relations'] = $relations;
            $table['indexes'] = $indexes;

            $normalized['tables'][] = $table;

        }
        return $normalized;
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
        return 'TBK_CREATE_' . $tableName . '_TABLE';
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



    public function updateTable($data) {

        //
        Log::info('update_table', $data);
    }


}