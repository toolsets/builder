<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 9:59 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Schema\Grammars;


use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;

class MySqlGrammar extends SnapshotGrammar
{






    /**
     * Compile the blueprint's column definitions.
     *
     * @param  \Illuminate\Database\Schema\Blueprint $blueprint
     * @return array
     */
    protected function getColumns(Blueprint $blueprint)
    {
        $columns = [];

        foreach ($blueprint->getAddedColumns() as $column) {
            //
            $column = $this->setDbType($column);

            $columns[] = $column;
        }

        return $columns;
    }



    protected function setDbType(Fluent $column)
    {

        $this->{'type'.ucfirst($column->type)}($column);

        return $column;
    }





    /**
     * ALL THE COMPILE METHODS
     */
    /**
     * Compile a create table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  \Illuminate\Database\Connection  $connection
     * @return string
     */
    public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
    {

        // TODO this is where we store a table create definition on memory


        $columns = $this->getColumns($blueprint);

        //$blueprint
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

//    /**
//     * Append the character set specifications to a command.
//     *
//     * @param  string  $sql
//     * @param  \Illuminate\Database\Connection  $connection
//     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
//     * @return string
//     */
//    protected function compileCreateEncoding($sql, Connection $connection, Blueprint $blueprint)
//    {
//        //TODO this is not likely needed
//
////        if (isset($blueprint->charset)) {
////            $sql .= ' default character set '.$blueprint->charset;
////        } elseif (! is_null($charset = $connection->getConfig('charset'))) {
////            $sql .= ' default character set '.$charset;
////        }
////
////        if (isset($blueprint->collation)) {
////            $sql .= ' collate '.$blueprint->collation;
////        } elseif (! is_null($collation = $connection->getConfig('collation'))) {
////            $sql .= ' collate '.$collation;
////        }
////
////        return $sql;
//    }

    /**
     * Compile an add column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileAdd(Blueprint $blueprint, Fluent $command)
    {

        //TODO this is where to add new column to an existing table

//        $table = $this->wrapTable($blueprint);
//
//        $columns = $this->prefixArray('add', $this->getColumns($blueprint));
//
//        return 'alter table '.$table.' '.implode(', ', $columns);
    }

    /**
     * Compile a primary key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compilePrimary(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileKey($blueprint, $command, 'primary key');
    }

    /**
     * Compile a unique key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileUnique(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileKey($blueprint, $command, 'unique');
    }

    /**
     * Compile a plain index key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileIndex(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileKey($blueprint, $command, 'index');
    }

    /**
     * Compile an index creation command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @param  string  $type
     * @return string
     */
    protected function compileKey(Blueprint $blueprint, Fluent $command, $type)
    {
        // TODO all the compile keys

//        $columns = $this->columnize($command->columns);
//
//        $table = $this->wrapTable($blueprint);
//
//        $index = $this->wrap($command->index);
//
//        $algorithm = $command->algorithm ? ' using '.$command->algorithm : '';
//
//        return "alter table {$table} add {$type} {$index}{$algorithm}($columns)";
    }

    /**
     * Compile a drop table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDrop(Blueprint $blueprint, Fluent $command)
    {
        // TODO set blueprint is dropped table, so that it will get removed from staging memory
        //return 'drop table '.$this->wrapTable($blueprint);
    }

    /**
     * Compile a drop table (if exists) command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropIfExists(Blueprint $blueprint, Fluent $command)
    {
        //TODO set blueprint is dropped table, so that it will get removed from staging memory
//        return 'drop table if exists '.$this->wrapTable($blueprint);
    }

    /**
     * Compile a drop column command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropColumn(Blueprint $blueprint, Fluent $command)
    {
        //TODO set blueprint to drop column
//        $columns = $this->prefixArray('drop', $this->wrapArray($command->columns));
//
//        $table = $this->wrapTable($blueprint);
//
//        return 'alter table '.$table.' '.implode(', ', $columns);
    }

    /**
     * Compile a drop primary key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropPrimary(Blueprint $blueprint, Fluent $command)
    {
        // TODO set blueprint to drop primary
//        return 'alter table '.$this->wrapTable($blueprint).' drop primary key';
    }

    /**
     * Compile a drop unique key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropUnique(Blueprint $blueprint, Fluent $command)
    {
        // TODO
//        $table = $this->wrapTable($blueprint);
//
//        $index = $this->wrap($command->index);
//
//        return "alter table {$table} drop index {$index}";
    }

    /**
     * Compile a drop index command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropIndex(Blueprint $blueprint, Fluent $command)
    {
        // TODO
//        $table = $this->wrapTable($blueprint);
//
//        $index = $this->wrap($command->index);
//
//        return "alter table {$table} drop index {$index}";
    }

    /**
     * Compile a drop foreign key command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileDropForeign(Blueprint $blueprint, Fluent $command)
    {
//        $table = $this->wrapTable($blueprint);
//
//        $index = $this->wrap($command->index);
//
//        return "alter table {$table} drop foreign key {$index}";
    }

    /**
     * Compile a rename table command.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @param  \Illuminate\Support\Fluent  $command
     * @return string
     */
    public function compileRename(Blueprint $blueprint, Fluent $command)
    {
        // TODO rename column
//        $from = $this->wrapTable($blueprint);
//
//        return "rename table {$from} to ".$this->wrapTable($command->to);
    }

//    /**
//     * Compile the command to enable foreign key constraints.
//     *
//     * @return string
//     */
//    public function compileEnableForeignKeyConstraints()
//    {
//        return 'SET FOREIGN_KEY_CHECKS=1;';
//    }
//
//    /**
//     * Compile the command to disable foreign key constraints.
//     *
//     * @return string
//     */
//    public function compileDisableForeignKeyConstraints()
//    {
//        return 'SET FOREIGN_KEY_CHECKS=0;';
//    }






    /**
     * ALL THE TYPES GETTERS
     */


    /**
     * Create the column definition for a char type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeChar(Fluent $column)
    {
        $column->db_type = "char({$column->length})";

        return $column;
    }

    /**
     * Create the column definition for a string type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeString(Fluent $column)
    {
        $column->db_type =  "varchar({$column->length})";
        return $column;
    }

    /**
     * Create the column definition for a text type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeText(Fluent $column)
    {
        return 'text';
    }

    /**
     * Create the column definition for a medium text type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeMediumText(Fluent $column)
    {
        return 'mediumtext';
    }

    /**
     * Create the column definition for a long text type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeLongText(Fluent $column)
    {
        return 'longtext';
    }

    /**
     * Create the column definition for a big integer type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeBigInteger(Fluent $column)
    {
        return 'bigint';
    }

    /**
     * Create the column definition for an integer type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeInteger(Fluent $column)
    {
        return 'int';
    }

    /**
     * Create the column definition for a medium integer type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeMediumInteger(Fluent $column)
    {
        return 'mediumint';
    }

    /**
     * Create the column definition for a tiny integer type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeTinyInteger(Fluent $column)
    {
        return 'tinyint';
    }

    /**
     * Create the column definition for a small integer type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeSmallInteger(Fluent $column)
    {
        return 'smallint';
    }

    /**
     * Create the column definition for a float type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeFloat(Fluent $column)
    {
        return $this->typeDouble($column);
    }

    /**
     * Create the column definition for a double type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeDouble(Fluent $column)
    {
        if ($column->total && $column->places) {
            return "double({$column->total}, {$column->places})";
        }

        return 'double';
    }

    /**
     * Create the column definition for a decimal type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeDecimal(Fluent $column)
    {
        return "decimal({$column->total}, {$column->places})";
    }

    /**
     * Create the column definition for a boolean type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeBoolean(Fluent $column)
    {
        return 'tinyint(1)';
    }

    /**
     * Create the column definition for an enum type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeEnum(Fluent $column)
    {
        return "enum('".implode("', '", $column->allowed)."')";
    }

    /**
     * Create the column definition for a json type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeJson(Fluent $column)
    {
        return 'json';
    }

    /**
     * Create the column definition for a jsonb type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeJsonb(Fluent $column)
    {
        return 'json';
    }

    /**
     * Create the column definition for a date type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeDate(Fluent $column)
    {
        return 'date';
    }

    /**
     * Create the column definition for a date-time type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeDateTime(Fluent $column)
    {
        return 'datetime';
    }

    /**
     * Create the column definition for a date-time type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeDateTimeTz(Fluent $column)
    {
        return 'datetime';
    }

    /**
     * Create the column definition for a time type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeTime(Fluent $column)
    {
        return 'time';
    }

    /**
     * Create the column definition for a time type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeTimeTz(Fluent $column)
    {
        return 'time';
    }

    /**
     * Create the column definition for a timestamp type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeTimestamp(Fluent $column)
    {
        if ($column->useCurrent) {
            return 'timestamp default CURRENT_TIMESTAMP';
        }

        return 'timestamp';
    }

    /**
     * Create the column definition for a timestamp type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeTimestampTz(Fluent $column)
    {
        if ($column->useCurrent) {
            return 'timestamp default CURRENT_TIMESTAMP';
        }

        return 'timestamp';
    }

    /**
     * Create the column definition for a binary type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeBinary(Fluent $column)
    {
        return 'blob';
    }

    /**
     * Create the column definition for a uuid type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeUuid(Fluent $column)
    {
        return 'char(36)';
    }

    /**
     * Create the column definition for an IP address type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeIpAddress(Fluent $column)
    {
        return 'varchar(45)';
    }

    /**
     * Create the column definition for a MAC address type.
     *
     * @param  \Illuminate\Support\Fluent  $column
     * @return string
     */
    protected function typeMacAddress(Fluent $column)
    {
        return 'varchar(17)';
    }



    /**
     * END OF TYPES
     */
}