<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 1:39 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration\Console;


use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Toolkits\LaravelBuilder\Services\Database\Migration\MigrationSnapshot;
use Toolkits\LaravelBuilder\Services\Database\Schema\Builder;

class MigrationReader extends BaseCommand
{

    protected $signature = 'builder:read-migrations';

    protected $name = 'builder';

    protected $description = 'Reads all migration files and generate snapshot of the database';

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
        $migrationRepository = $this->migrator->getRepository();
        if ($migrationRepository->repositoryExists() == false) {
            $migrationRepository->createRepository();
        }

        Builder::$snapshot = true;

        $this->migrator->runSnapshot($this->getMigrationPaths(), [
            'pretend' => true
        ]);

        Builder::$snapshot = false;

        $shots = MigrationSnapshot::$shots;

        $filepath = $shots->save();

        $this->comment('DB connection snapshot saved to ' .$filepath);
    }




}