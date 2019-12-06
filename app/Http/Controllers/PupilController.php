<?php

namespace App\Http\Controllers;

use App\Groups;
use App\Pupils;
use App\Teachers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class PupilController extends Controller
{


    public function items(Request $request)
    {
        return Pupils::city()->filter(Input::all())->orderBy('id', 'desc')->paginate(20);
    }

    public function all(Request $request)
    {
        return Pupils::all();
    }


    public function item($id)
    {
        return Pupils::where('id', $id)->first();
    }
    public function export_xlsx()
    {
        $url =  Pupils::exportxlsx();
        header("Location: /".$url);
        die();
    }
    public function save(request $request)
    {

        $id = $request->get('id') ? $request->get('id') : 0;

        $this->validate($request, [
            'firstName' => 'required|string|max:100',
            'secondName' => 'required|string|max:100',
            'birthDate' => 'nullable|date',
            'phone' => 'required',
        ]);


        $pupil = $id ? Pupils::find($id) : new Pupils();

        $pupil->firstName = $request->get('firstName');
        $pupil->secondName = $request->get('secondName');
        $pupil->birthDate = $request->get('birthDate');
        $pupil->phone = $request->get('phone');
        $pupil->save();

    }


    public function delete($id)
    {
        $pupil = Pupils::find($id);
        Pupils::destroy($id);
    }
}
