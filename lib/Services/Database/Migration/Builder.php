<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 3:06 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder as SchemaBuilder;

class Builder extends SchemaBuilder
{

    public static $snapshot = false;

    /**
     * Execute the blueprint to build / modify the table.
     *
     * @param  \Illuminate\Database\Schema\Blueprint  $blueprint
     * @return void
     */
    protected function build(Blueprint $blueprint)
    {
        // when snapshot is enabled, the blueprint gets parse to YAML DB snapshot files
        if (static::$snapshot) {
            MigrationSnapshot::capture($blueprint, $this->connection);
        } else {
            $blueprint->build($this->connection, $this->grammar);
        }
    }

}