<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Database\SQLiteConnection as LaravelSQLiteConnection;
use Toolkits\LaravelBuilder\Services\Database\Schema\Builder;
use Toolkits\LaravelBuilder\Services\Database\Schema\Grammars\SQLiteGrammar;
use Toolkits\LaravelBuilder\Services\Database\Schema\SnapshotBlueprint;

class SQLiteConnection extends LaravelSQLiteConnection
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