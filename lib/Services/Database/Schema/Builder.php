<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 1:17 PM
 */

namespace Toolsets\Builder\Services\Database\Schema;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder as SchemaBuilder;
use Illuminate\Support\Facades\Artisan;
use Toolsets\Builder\Services\Database\Migration\MigrationSnapshot;

class Builder extends SchemaBuilder
{

    public static $snapshot = false;

    public $sToolset = true;


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