<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolsets\Builder\Services\Database;


use Illuminate\Database\PostgresConnection as LaravelPostgresConnection;
use Toolsets\Builder\Services\Database\Schema\Builder;
use Toolsets\Builder\Services\Database\Schema\Grammars\PostgresGrammar;
use Toolsets\Builder\Services\Database\Schema\PostgresBuilder;


class PostgresConnection extends LaravelPostgresConnection
{

    /**
     * Get a schema builder instance for the connection.
     *
     * @return PostgresBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new PostgresBuilder($this);
    }
}