<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Teachers extends Model
{
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
            'birthDate' => 'nullable',
            'phone' => 'required',
        ];
    }

    public static function getTeacherFullName()
    {

        $obj = array();
        $arr = array();
        $teachers = Teachers::all();

        foreach ($teachers as $teacher){

            $arr = array(
                'value' => $teacher['id'],
                'label' => $teacher['firstName'] . ' ' . $teacher['secondName'],
            );
            array_push($obj,$arr);

        }
        return $obj;
    }

    public function groups()
    {
        return $this->hasMany(Groups::class);
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

        if($filters['keep']){
            Log::info("keep is working");
            $keep_status = $filters['keep'];
            if ($keep_status==2) {
                Log::info("keep 2 is working");
                $query->whereHas('groups')->exists();
            }else {
                Log::info("keep 1 is working");
                $query->whereDoesntHave('groups');
            }
        }


        return $query;
    }

}
