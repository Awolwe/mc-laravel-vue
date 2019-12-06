<?php

namespace App\Http\Controllers;

use App\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TeacherController extends Controller
{
    public function items(Request $request)
    {

        return Teachers::filter(Input::all())->orderBy('id', 'desc')->paginate(20);
    }

    public function all(Request $request)
    {
        return Teachers::all();
    }

    public function item($id)
    {
        return Teachers::with(['groups'])->where('id', $id)->first();
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


        $teacher = $id ? Teachers::find($id) : new Teachers();

        $teacher->firstName = $request->get('firstName');
        $teacher->secondName = $request->get('secondName');
        $teacher->birthDate = $request->get('birthDate');
        $teacher->phone = $request->get('phone');
        $teacher->save();

    }

    public function delete($id)
    {
        $teacher = Teachers::find($id);
        Teachers::destroy($id);
    }
}
