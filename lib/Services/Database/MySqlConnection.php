<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolsets\Builder\Services\Database;


use Illuminate\Database\MySqlConnection as LaravelMySqlConnection;
use Toolsets\Builder\Services\Database\Schema\MySqlBuilder;
use Toolsets\Builder\Services\Database\Schema\SnapshotBlueprint;


class MySqlConnection extends LaravelMySqlConnection
{

    /**
     * Get a schema builder instance for the connection.
     *
     * @return MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new MySqlBuilder($this);
    }
}