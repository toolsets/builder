<?php

use Illuminate\Support\Facades\Route;
use Toolkits\LaravelBuilder\Repositories\DatabaseTables;

Route::get('/', function ()
{
    return view('tlb::app');
});


Route::group(['prefix' => 'ajax'], function ()
{

    Route::get('tables', function (DatabaseTables $repo) {

        return $repo->getTables();
    });

    Route::post('tables', function (\Illuminate\Http\Request $request, DatabaseTables $repo) {

        return $repo->createNewTable($request->all());
    });
});
