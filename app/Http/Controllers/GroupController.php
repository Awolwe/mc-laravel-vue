<?php

namespace App\Http\Controllers;

use App\Groups;
use App\Pupils;
use App\Teachers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class GroupController extends Controller
{
    public function items(Request $request)
    {
        return Groups::with('teachers')->city()->filter(Input::all())->orderBy('id', 'desc')->paginate(20);
    }

    public function all(Request $request)
    {
        return Groups::all();
    }

    public function item($id)
    {
        return Groups::with('teachers', 'pupils')->where('id', $id)->first();
    }
    public function sync($id, request $request)
    {
        $names = $request->get('names');
        $group = Groups::find($id);
        return $group->pupils()->attach($names);

    }
    public function NotIncludeIDs($id)
    {
        $pupils = Groups::with('pupils')->where('id', $id)->get();
        $arrPupils = json_decode(json_encode($pupils[0]->pupils), true);
        $pupilIDs = array_column($arrPupils, 'id');
        return json_decode(json_encode(Pupils::whereNotIn('id', $pupilIDs)->get()), true);
    }
    public function save(request $request)
    {

        $id = $request->get('id') ? $request->get('id') : 0;

        $this->validate($request, [
            'teacher_id' => 'required|integer|exists:teachers,id',
            'type_id' => 'required|integer', //exists:type,id
            'name' => 'required|string|max:100',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'status' => 'required|integer',
        ]);


        $group = $id ? Groups::find($id) : new Groups();

        $group->firstName = $request->get('firstName');
        $group->secondName = $request->get('secondName');
        $group->birthDate = $request->get('birthDate');
        $group->phone = $request->get('phone');
        $group->save();

    }

    public function delete($id)
    {
        $user = Groups::find($id);
        Groups::destroy($id);
    }
}
