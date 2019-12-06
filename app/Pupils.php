<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Pupils extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'firstName',
        'secondName',
        'birthDate',
        'phone',
    ];


    public static function rules($id = 0)
    {
        return [
            'firstName' => 'required',
            'secondName' => 'required',
            'birthDate' => '->nullable',
            'phone' => 'required',
        ];
    }
    public static function getPupilsFullName()
    {

        $obj = array();
        $arr = array();
        $pupils = Pupils::all();

        foreach ($pupils as $pupil){

            $arr = array(
                'value' => $pupil['id'],
                'label' => $pupil['firstName'] . ' ' . $pupil['secondName'],
            );
            array_push($obj,$arr);

        }
        return $obj;
    }



    public function groups()
    {
        return $this->belongsToMany(Groups::class, 'pupils_and_groups');
    }

    public function scopeCity($query)
    {
        Log::info('ScopeCity is working');
        $user = Auth::user();
        if ($user->hasCity())
         $query->Where('city', '=', $user->city);
        return $query;

    }
    public function scopeFilter($query, $filters)
    {

        if(isset($filters['search_text'])){
            $search_text = $filters['search_text'];

            $query->where(function($query) use($search_text) {
                $query->where('firstName', 'like', '%'.$search_text.'%');
                $query->orWhere('secondName', 'like', '%'.$search_text.'%');
                $query->orWhere('phone', 'like', '%'.$search_text.'%');
            });

        }
        if($filters['teacher_id']){
            Log::info("teacher id is working");
            $teacher_id = $filters['teacher_id'];
            $query -> whereHas('groups', function ($query) use($teacher_id) {

                $query->Where('teachers_id', '=', $teacher_id);

            });
        }


        return $query;
    }
    public static function exportxlsx(){
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $arrayData = ['Имя', 'Фамилия', 'Дата Рождения', 'Телефон', 'создан', 'изменен'];
        $spreadsheet->getActiveSheet()
            ->fromArray( $arrayData );
        $pupils = Pupils::all();
        $i = 2;
        foreach($pupils as $pupil) {
            $arrayData = [$pupil['firstName'], $pupil['secondName'], $pupil['birthDate'], $pupil['phone'], $pupil['created_at'], $pupil['updated_at']];
            try {
                $spreadsheet->getActiveSheet()
                    ->fromArray($arrayData, NULL,        // Array values with this value will not be set
                        'A' . $i);
            } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                Log::info($e);
            }
            $i++;
        }
        $writer = new Xlsx($spreadsheet);
        $name = str_random(10).'.xlsx';
        $writer->save($name);
        return $name;
    }
}
