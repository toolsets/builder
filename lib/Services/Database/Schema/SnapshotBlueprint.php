<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 20/12/2016
 * Time: 9:13 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database\Schema;



use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar;

class SnapshotBlueprint extends Blueprint
{

    protected $snapshot = [];


    /**
     * Execute the blueprint against the database.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  \Illuminate\Database\Schema\Grammars\Grammar $grammar
     * @return void
     */
    public function build(Connection $connection, Grammar $grammar)
    {

        $this->addImpliedCommands();

        foreach ($this->commands as $command) {
            $method = 'compile'.ucfirst($command->name);
print_r('method: '.$method);
            if (method_exists($grammar, $method)) {
                $grammar->$method($this, $command, $connection);
            }
        }

        dd('from snapshot blueprint', $this->getColumns());


//        foreach ($this->toSql($connection, $grammar) as $statement) {
//           // dd($statement);
//            print_r($statement);
//            print_r("\n");
//            print_r("\n");
//            print_r("\n");
//            //$connection->statement($statement);
//        }
    }


    /**
     * Get the raw SQL statements for the blueprint.
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @param  \Illuminate\Database\Schema\Grammars\Grammar  $grammar
     * @return array
     */
    public function toSql(Connection $connection, Grammar $grammar)
    {
        $this->addImpliedCommands();

//        dd($this->commands);

        $statements = [];

        // Each type of command has a corresponding compiler function on the schema
        // grammar which is used to build the necessary SQL statements to build
        // the blueprint element, so we'll just call that compilers function.
        foreach ($this->commands as $command) {
            $method = 'compile'.ucfirst($command->name);

            print_r($method);
            print_r("\n");

            if (method_exists($grammar, $method)) {
                if (! is_null($sql = $grammar->$method($this, $command, $connection))) {
                   // $statements = array_merge($statements, (array) $sql);
                }
            }
        }

        return $statements;
    }


    /**
     * Add the commands that are implied by the blueprint.
     *
     * @return void
     */
    protected function addImpliedCommands()
    {
        if (count($this->getAddedColumns()) > 0 && ! $this->creating()) {
            array_unshift($this->commands, $this->createCommand('add'));
        }

        if (count($this->getChangedColumns()) > 0 && ! $this->creating()) {
            array_unshift($this->commands, $this->createCommand('change'));
        }

        $this->addFluentIndexes();
    }
}