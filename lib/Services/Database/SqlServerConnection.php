<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolsets\LaravelBuilder\Services\Database;


use Illuminate\Database\SqlServerConnection as LaravelSqlServerConnection;
use Toolsets\LaravelBuilder\Services\Database\Migration\Builder;
use Toolsets\LaravelBuilder\Services\Database\Schema\Grammars\SqlServerGrammar;


class SqlServerConnection extends LaravelSqlServerConnection
{

    /**
     * Get a schema builder instance for the connection.
     *
     * @return Builder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        $builder =  new Builder($this);

        return $builder;
    }
}