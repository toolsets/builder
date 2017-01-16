<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 15/01/2017
 * Time: 3:39 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Migration;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use InvalidArgumentException;

class MigrationCreator
{

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;


    /**
     * Create a new migration creator instance.
     *
     * @param  Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }


    /**
     * Create a new migration at the given path.
     *
     * @param  string  $name
     * @param  string  $path
     * @param  string  $table
     * @param  bool    $create
     * @return string
     * @throws \Exception
     */
    public function create($name, $path, $definitions = [], $create = false)
    {
        $table = $definitions['name'];

        $this->ensureMigrationDoesntAlreadyExist($name);

        $path = $this->getPath($name, $path);

        // First we will get the stub file for the migration, which serves as a type
        // of template for the migration. Once we have those we will populate the
        // various place-holders, save the file, and run the post create event.
        $stub = $this->getStub($table, $create);

        $this->files->put($path, $this->populateStub($name, $stub, $definitions));

        return $path;
    }


    /**
     * Ensure that a migration with the given name doesn't already exist.
     *
     * @param  string  $name
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function ensureMigrationDoesntAlreadyExist($name)
    {
        if (class_exists($className = $this->getClassName($name))) {
            throw new InvalidArgumentException("A $className migration already exists.");
        }
    }


    /**
     * Get the migration stub file.
     *
     * @param  string  $table
     * @param  bool    $create
     * @return string
     */
    protected function getStub($create = true)
    {
        $stub = $create ? 'create.stub' : 'update.stub';

        return $this->files->get($this->getStubPath()."/{$stub}");
    }


    /**
     * Populate the place-holders in the migration stub.
     *
     * @param  string  $name
     * @param  string  $stub
     * @param  string  $table
     * @return string
     */
    protected function populateStub($name, $stub, $definitions)
    {
        $table = $definitions['name'];

        $stub = str_replace('{MIGRATION_CLASS}', $this->getClassName($name), $stub);

        // Here we will replace the table place-holders with the table specified by
        // the developer, which is useful for quickly creating a tables creation
        // or update migration from the console instead of typing it manually.
        if (! is_null($table)) {
            $stub = str_replace('{TABLE_NAME}', $table, $stub);
        }

        // Here we will replace the place-holder with table column definitions
        if (isset($definitions['UP'])) {
            $stub = $this->populateTableColumnsForUp($stub, $definitions['UP']);
        }

        if (isset($definitions['DOWN'])) {
            $stub = $this->populateTableColumnsForDown($stub, $definitions['DOWN']);
        }

        return $stub;
    }


    /**
     * Get the class name of a migration name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getClassName($name)
    {
        return Str::studly($name);
    }


    /**
     * Get the full path name to the migration.
     *
     * @param  string  $name
     * @param  string  $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path.'/'.$this->getDatePrefix().'_'.$name.'.php';
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * Get the path to the stubs.
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__.'/stubs';
    }

    /**
     * Get the filesystem instance.
     *
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->files;
    }




    protected function populateTableColumnsForUp($stub, $data)
    {

        $relationsOutput = '';
        $indexesOutput = '';
        $primaryKeyColumn = null;
        $tabSpace = '    ';
        $threeTabs = $tabSpace . $tabSpace . $tabSpace;

        $columnsOutput = $this->makeNewColumns($stub, $data, $threeTabs);

        $relationsOutput = $this->makeNewRelations($stub, $data, $threeTabs);

        //indexes
        if (!empty($data['indexes'])) {
            $output = '';
            foreach ($data['indexes'] as $indexCol) {
                $columns = $indexCol['columns'];
                $type = $indexCol['type'];
                $columnsFormat = "['". implode("','", $columns) ."']";

                $template = "\$table->%s(%s);\n";
                $output =  sprintf($template, $type, $columnsFormat);

                $indexesOutput .= $threeTabs . $output;
            }

            $indexesOutput .= "\n\r";
        }

        $finalOutput = $columnsOutput  . $relationsOutput  . "\n\r" . $indexesOutput;

        return str_replace('{TABLE_DEFINITIONS_UP}', $finalOutput, $stub);
    }


    protected function makeNewColumns($stub, $data, $spacing)
    {
        $columnsOutput = '';

        foreach ($data['columns'] as $col) {
            $length = $col['length'];
            $column = $col['name'];
            $type = $col['type'];
            $default = $col['default'];
            $nullable = $col['nullable'];
            $pkIndex = Str::endsWith($type, 'ncrements') ? false : $col['pk'] == true;


            if (!empty($length)) {

                if ($type == 'enum') {
                    $length = explode(',', $length);
                    $length = array_map(function ($item) {
                        return "'". trim($item) ."'";
                    }, $length);
                    $length = implode(', ', $length);
                    $length = "[$length]";

                    $template = "\$table->%s('%s', %s)";
                }
                $tableString = sprintf($template, $type, $column, $length);
            } else {
                $tableString = sprintf("\$table->%s('%s')", $type, $column);
            }

            if ($nullable == true) {
                $tableString .= '->nullable()';
            }

            if (!empty($default)) {
                $tableString .= sprintf("->default('%s')", $default);
            }

            if ($pkIndex === true) {
                $tableString .= sprintf('->primary()');
            }

            $tableString .= ";\n";

            $columnsOutput .= $spacing . $tableString;
        }

        $columnsOutput .= "\n\r";

        return $columnsOutput;
    }

    protected function populateTableColumnsForDown($stub, $data)
    {
        return str_replace('{TABLE_DEFINITIONS_DOWN}', '//', $stub);
    }

    public function makeNewRelations($stub, $data, $spacing)
    {
        //releations
        $relationsOutput = '';
        if (!empty($data['relations'])) {
            foreach ($data['relations'] as $relation) {
                $column = $relation['column'];
                $fk_column = $relation['fk_column'];
                $fk_table = $relation['fk_table'];
                $on_update = $relation['on_update'];
                $on_delete = $relation['on_delete'];

                $template = "\$table->foreign('%s')->references('%s')->on('%s')";

                $output = sprintf($template, $column, $fk_column, $fk_table);

                if ($on_update != 'none') {
                    switch ($on_update) {
                        case 'set_null':
                            $output .= "->onUpdate('set null')";
                            break;

                        case 'cascade':
                            $output .= "->onUpdate('cascade')";
                            break;

                        case 'restrict':
                            $output .= "->onUpdate('restrict')";
                            break;
                    }
                }

                if ($on_delete != 'none') {
                    switch ($on_delete) {
                        case 'set_null':
                            $output .= "->onDelete('set null')";
                            break;

                        case 'cascade':
                            $output .= "->onDelete('cascade')";
                            break;

                        case 'restrict':
                            $output .= "->onDelete('restrict')";
                            break;
                    }
                }

                $output .= ";\n";

                $relationsOutput .= $spacing . $output;

            }

            $relationsOutput .= "\n\r";
        }

        return $relationsOutput;
    }
}