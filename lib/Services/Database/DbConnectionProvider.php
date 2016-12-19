<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 19/12/2016
 * Time: 7:19 PM
 */

namespace Toolkits\LaravelBuilder\Services\Database;


use Illuminate\Support\ServiceProvider;

class DbConnectionProvider extends ServiceProvider
{


    public function register()
    {
        $drivers = ['mysql', 'pgsql', 'sqlite', 'sqlsrv'];

        $this->app->singleton('db.connection.mysql', function ($app, $parameters) {
            return $this->getConnection('mysql', $parameters);
        });

//        foreach ($drivers as $driver) {
//            $key = 'db.connection.'.$driver;
//            $this->app->singleton($key, function ($app, $parameters) use ($driver) {
//                return $this->getConnection($driver, $parameters);
//            });
//        }
    }

    private function getConnection($driver, $parameters)
    {
        list($connection, $database, $prefix, $config) = $parameters;

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
    }
}