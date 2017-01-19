<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 16/12/2016
 * Time: 2:42 AM
 */

namespace Toolsets\Builder\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class MakeCommand extends Command
{

    protected $signature = 'builder:install';

    protected $description = 'Creates a builder project within Laravel App';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'builder';

    protected $project_path;


    public function handle()
    {
        $root_path ='toolsets';

        $project_path = $root_path . '/' . $this->name ;
        $project_real_path = storage_path($project_path);

        if (!file_exists($project_real_path)) {
            Storage::makeDirectory($project_path);
            $this->comment('Project files directory created: ' . $project_real_path);

            $this->initializeNewProject($project_path);
        } else {
            $this->comment('Project files already exists');
        }

        Artisan::call('vendor:publish', [
            '--tag' => 'toolsets',
            '--force' => true
        ]);

    }


    protected function initializeNewProject($project_path)
    {
        $this->project_path = $project_path;

        Storage::put($this->getPath('config.yaml'), $this->toYaml($this->getInitialConfig()));
    }


    protected function getPath($path)
    {
        return $this->project_path . '/' . $path;
    }

    protected function toYaml(array $data)
    {
        return Yaml::dump($data);
    }

    protected function getInitialConfig()
    {
        return [
          'database' => [
              'tables' => [],
              'migrations' => []
            ],
            'models' => [],
            'forms' => [],
            'modules' => [],
            'permissions' => [],
            'localization' => [
                'en' => $this->getPath('local/en/lang.php')
            ]
        ];
    }

}
