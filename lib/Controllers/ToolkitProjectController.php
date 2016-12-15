<?php
/**
 * Created by PhpStorm.
 * User: raf
 * Date: 15/12/2016
 * Time: 9:37 PM
 */

namespace Toolkits\LaravelBuilder\Controllers;


use Illuminate\Routing\Controller;

class ToolkitProjectController extends Controller
{


    public function index()
    {
        return 'hellow from Controller ' . tbl_trans('toolkit.name');
    }


}