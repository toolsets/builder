<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Database\PostgresConnection as LaravelPostgresConnection;
use Toolkits\LaravelBuilder\Services\Database\Schema\Builder;
use Toolkits\LaravelBuilder\Services\Database\Schema\Grammars\PostgresGrammar;
use Toolkits\LaravelBuilder\Services\Database\Schema\PostgresBuilder;


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
            if (Builder::$snapshot) {
                $this->setSchemaGrammar(new PostgresGrammar);
            } else {
                $this->useDefaultSchemaGrammar();
            }
        }

        $builder = new PostgresBuilder($this);

        if (Builder::$snapshot) {
            //when snapshot is enabled, include custom blueprint
            $builder->blueprintResolver(function ($table, $callback) {

                return new SnapshotBlueprint($table, $callback);
            });
        }

        return $builder;
    }
}