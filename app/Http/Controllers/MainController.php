<?php
/**
 * Created by PhpStorm.
 * User: Yurituski
 * Date: 19.11.2018
 * Time: 23:24
 */

namespace App\Http\Controllers;


use App\Pupils;
use App\Role;
use App\Teachers;
use Illuminate\Support\Facades\Request;

class MainController extends Controller
{

    public function data(Request $request)
    {

        $data = [];
        $data['roles'] = Role::all();
        $data['teachers'] = Teachers::all();
        $data['teachersFullName'] = Teachers::getTeacherFullName();
        $data['pupilsFullName'] = Pupils::getPupilsFullName();
        $data['pupils'] = Pupils::all();
        return $data;

    }
}