<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Database\MySqlConnection as LaravelMySqlConnection;
use Toolkits\LaravelBuilder\Services\Database\Schema\Builder;
use Toolkits\LaravelBuilder\Services\Database\Schema\Grammars\MySqlGrammar;
use Toolkits\LaravelBuilder\Services\Database\Schema\MySqlBuilder;
use Toolkits\LaravelBuilder\Services\Database\Schema\SnapshotBlueprint;


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

        $builder =  new MySqlBuilder($this);

        return $builder;

    }
}