<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 1:19 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Schema;


class PostgresBuilder extends Builder
{
    /**
     * Determine if the given table exists.
     *
     * @param  string  $table
     * @return bool
     */
    public function hasTable($table)
    {
        if (is_array($schema = $this->connection->getConfig('schema'))) {
            $schema = head($schema);
        }

        $table = $this->connection->getTablePrefix().$table;

        return count($this->connection->select(
                $this->grammar->compileTableExists(), [$schema, $table]
            )) > 0;
    }
}