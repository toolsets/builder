<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:33 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Database\SQLiteConnection as LaravelSQLiteConnection;
use Toolkits\LaravelBuilder\Services\Database\Migration\Builder;

class SQLiteConnection extends LaravelSQLiteConnection
{

//    /**
//  TODO THIS METHOD IS NOT SUPPORTED, NEED TO FIND AN ALTERNATIVE PLACE TO HOOK
//     * Get a schema builder instance for the connection.
//     *
//     * @return Builder
//     */
//    public function getSchemaBuilder()
//    {
//        if (is_null($this->schemaGrammar)) {
//            $this->useDefaultSchemaGrammar();
//        }
//
//        return new Builder($this);
//    }

}