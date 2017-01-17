<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolsets\LaravelBuilder\Services\Database;


use Illuminate\Database\SQLiteConnection as LaravelSQLiteConnection;
use Toolsets\LaravelBuilder\Services\Database\Schema\Builder;
use Toolsets\LaravelBuilder\Services\Database\Schema\Grammars\SQLiteGrammar;
use Toolsets\LaravelBuilder\Services\Database\Schema\SnapshotBlueprint;

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

        return new Builder($this);
    }

}