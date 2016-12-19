<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 1:39 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration\Console;


use Closure;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Yaml\Yaml;
use Toolkits\LaravelBuilder\Services\Database\Migration\Builder;
use Toolkits\LaravelBuilder\Services\Database\Migration\MigrationSnapshot;

class MigrationReader extends BaseCommand
{

    protected $signature = 'builder:reader';

    protected $name = 'builder';

    protected $description = 'Creates a builder project within Laravel App';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    protected $builderInstance;


    /**
     * Create a new migration command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     * @return void
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }



    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        Builder::$snapshot = true;

        $this->migrator->run($this->getMigrationPaths(), [
            'pretend' => true
        ]);

        Builder::$snapshot = false;

        $shots = MigrationSnapshot::$shots;

        $filepath = $shots->save();

        $this->comment('DB connection snapshot saved to ' .$filepath);
    }




}