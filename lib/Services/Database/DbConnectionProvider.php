<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:19 PM
 */

namespace Toolsets\LaravelBuilder\Services\Database;


use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class DbConnectionProvider extends ServiceProvider
{

    public function register()
    {
        $drivers = ['mysql', 'pgsql', 'sqlite', 'sqlsrv'];

        foreach ($drivers as $driver) {

            Connection::resolverFor($driver, function($connection, $database, $prefix, $config) use ($driver) {

                switch ($driver) {
                    case 'mysql':
                        return new MySqlConnection($connection, $database, $prefix, $config);
                    case 'pgsql':
                        return new PostgresConnection($connection, $database, $prefix, $config);
                    case 'sqlite':
                        return new SQLiteConnection($connection, $database, $prefix, $config);
                    case 'sqlsrv':
                        return new SqlServerConnection($connection, $database, $prefix, $config);
                }
            });
        }
    }
}