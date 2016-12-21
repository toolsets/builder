<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 9:59 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Schema\Grammars;


use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\Grammar;
use Illuminate\Support\Fluent;
use Toolkits\LaravelBuilder\Services\Database\Schema\SnapshotBlueprint as Blueprint;

abstract class SnapshotGrammar extends Grammar
{

    protected $snapshots = [];


//    protected function getColumns(Blueprint $blueprint)
//    {
//
//        return $blueprint->getAddedColumns();
////        $columns = [];
////
////        foreach ($blueprint->getAddedColumns() as $column) {
////            // Each of the column types have their own compiler functions which are tasked
////            // with turning the column definition into its SQL format for this platform
////            // used by the connection. The column's modifiers are compiled and added.
////            //$sql = $this->wrap($column).' '.$this->getType($column);
////
////            $columns[] = $column; //$this->addModifiers($sql, $blueprint, $column);
////        }
////
////        return $columns;
//    }






    /**
     * Compile a create table command.
     *
     * @param SnapshotBlueprint $blueprint
     * @param \Illuminate\Support\Fluent|Fluent $command
     * @param \Illuminate\Database\Connection|Connection $connection
     * @return string
     */
    public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
          $connectionName = $connection->getName();
          $databaseName = $connection->getDatabaseName();
          $tableName = $blueprint->getTable();

          $columns = $this->getColumns($blueprint);


          $databases = [];
          //check if database name already exists under connection
          if (isset($this->snapshots[$connectionName][$databaseName])) {
              $databases = $this->snapshots[$connectionName][$databaseName];
          }

          if(!isset($databases[$databaseName]))
          {
              $databases[$databaseName] = [];
          }


            $column_details = [
                'name' => '',
                'type' => '',
                'length' => '',
                'unsigned' => false,
                'allow_null' => false,
                'auto_increment' => false,
                'primary_key' => false,
                'default' => NULL
            ];

          $updated_cols = [];
          foreach($columns as $column)
          {
              $details = array_merge($column_details, $column->toArray());
              $updated_cols[] = new Fluent($details);
          }




          $databases[$databaseName][$tableName] = [
              'name' => $tableName,
              'columns' => $updated_cols
          ];

          $this->snapshots[$connectionName] = $databases;

//dd($this->snapshots[$connectionName]);

//        $columns = implode(', ', $this->getColumns($blueprint));
//
//        $sql = $blueprint->temporary ? 'create temporary' : 'create';
//
//        $sql .= ' table '.$this->wrapTable($blueprint)." ($columns)";
//
//        // Once we have the primary SQL, we can add the encoding option to the SQL for
//        // the table.  Then, we can check if a storage engine has been supplied for
//        // the table. If so, we will add the engine declaration to the SQL query.
//        $sql = $this->compileCreateEncoding($sql, $connection, $blueprint);
//
//        if (isset($blueprint->engine)) {
//            $sql .= ' engine = '.$blueprint->engine;
//        } elseif (! is_null($engine = $connection->getConfig('engine'))) {
//            $sql .= ' engine = '.$engine;
//        }
//
//        return $sql;
    }


    /**
     * Compile a unique key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileUnique(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        return $this->compileKey($blueprint, $command, 'unique', $connection);
    }



    /**
     * Compile a primary key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compilePrimary(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $command->name(null);

        return $this->compileKey($blueprint, $command, 'primary key', $connection);
    }


    /**
     * Compile an add column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileAdd(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $connectionName = $connection->getName();
        $databaseName = $connection->getDatabaseName();
        $tableName = $blueprint->getTable();

        //$columns = $this->prefixArray('add', $this->getColumns($blueprint));

        if(!isset($this->snapshots[$connectionName][$databaseName][$tableName]))
        {
            $this->snapshots[$connectionName][$databaseName][$tableName] = [
                'name' => $tableName,
                'columns' => []
            ];
        }

        $snapshot = $this->snapshots[$connectionName][$databaseName][$tableName];

        $columns = $this->getColumns($blueprint, $connection);

        $column_details = [
            'name' => '',
            'type' => '',
            'length' => '',
            'unsigned' => false,
            'allow_null' => false,
            'auto_increment' => false,
            'primary_key' => false,
            'default' => NULL
        ];

        $update_cols = $snapshot['columns'];
        foreach($columns as $column)
        {
            $details = array_merge($column_details, $column->toArray());
            $update_cols[] = new Fluent($details);
        }

        $snapshot['columns'] = $update_cols;
        $this->snapshots[$connectionName][$databaseName][$tableName] = $snapshot;

    }


    /**
     * Compile a plain index key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileIndex(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        return $this->compileKey($blueprint, $command, 'index', $connection);
    }


    /**
     * Compile a drop index command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropIndex(Blueprint $blueprint, Fluent $command, Connection $connection)
    {
        $connectionName = $connection->getName();
        $databaseName = $connection->getDatabaseName();
        $tableName = $blueprint->getTable();

        // TODO needs to drop indexes
    }



    protected function compileKey(Blueprint $blueprint, Fluent $command, $type, Connection $connection)
    {
        $connectionName = $connection->getName();
        $databaseName = $connection->getDatabaseName();
        $tableName = $blueprint->getTable();

        $snapshot = $this->snapshots[$connectionName][$databaseName][$tableName];

        //$previousBlueprint->getAddedColumns();

print_r($type);
print_r("\n");
        $columns = $command->columns;
        switch($type)
        {
            case 'primary key':
dd('primary key', $columns);
                break;
        }
    }
}