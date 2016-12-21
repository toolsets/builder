<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Database\SqlServerConnection as LaravelSqlServerConnection;
use Toolkits\LaravelBuilder\Services\Database\Migration\Builder;
use Toolkits\LaravelBuilder\Services\Database\Schema\Grammars\SqlServerGrammar;


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
//            if (Builder::$snapshot) {
//                $this->setSchemaGrammar(new SqlServerGrammar);
//            } else {
                $this->useDefaultSchemaGrammar();
//            }
        }

        $builder =  new Builder($this);

//        if (Builder::$snapshot) {
//            //when snapshot is enabled, include custom blueprint
//            $builder->blueprintResolver(function ($table, $callback) {
//
//                return new SnapshotBlueprint($table, $callback);
//            });
//        }

        return $builder;
    }
}