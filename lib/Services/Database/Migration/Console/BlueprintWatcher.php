<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 6:23 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration\Console;


use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;

class BlueprintWatcher extends Blueprint
{

    public static $snapshot = [];

    /**
     * Execute the blueprint against the database.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  \Illuminate\Database\Schema\Grammars\Grammar $grammar
     * @return void
     */
    public function build(Connection $connection, Grammar $grammar)
    {
        $snapshot = static::$snapshot;

        $table = $this->getTable();

        if (!isset($snapshot[$table])) {
            $snapshot[$table] = [];
        }

        $snapshot[$table][] = $this->getColumns();

        static::$snapshot = $snapshot;
    }
}