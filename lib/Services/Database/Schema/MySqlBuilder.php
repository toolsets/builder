<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 1:19 PM
 */

namespace Toolsets\LaravelBuilder\Services\Database\Schema;


class MySqlBuilder extends Builder
{

    /**
     * Determine if the given table exists.
     *
     * @param  string  $table
     * @return bool
     */
    public function hasTable($table)
    {
        $table = $this->connection->getTablePrefix().$table;

        return count($this->connection->select(
                $this->grammar->compileTableExists(), [$this->connection->getDatabaseName(), $table]
            )) > 0;
    }

    /**
     * Get the column listing for a given table.
     *
     * @param  string  $table
     * @return array
     */
    public function getColumnListing($table)
    {
        $table = $this->connection->getTablePrefix().$table;

        $results = $this->connection->select(
            $this->grammar->compileColumnListing(), [$this->connection->getDatabaseName(), $table]
        );

        return $this->connection->getPostProcessor()->processColumnListing($results);
    }

}