<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 16/12/2016
 * Time: 2:42 AM
 */

namespace Toolkits\LaravelBuilder\Console;

use Illuminate\Console\Command;

class MakeCommand extends Command
{

    protected $signature = 'make:project {name}';

    protected $description = 'Creates a project within Laravel App';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'builder';
}