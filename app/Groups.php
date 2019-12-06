<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Groups extends Model
{
    protected $fillable = [
        'teacher_id',
        'type_id',
        'name',
        'startDate',
        'endDate',
        'status',
    ];

    public static function rules($id = 0)
    {
        return [
            'teachers_id' => 'required',
            'type_id' => 'required',
            'name' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
            'status' => 'required',
        ];
    }
    public function teachers()
    {
        return $this->belongsTo(Teachers::class, 'teachers_id');
    }
    public function pupils()
    {
        return $this->belongsToMany(Pupils::class, 'pupils_and_groups');
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
            /*$groups_count = Groups::where('name','like', '%'.$search_text.'%')->count();*/
            if($filters['chooser'] == 1)
                $query->where(function($query) use($search_text) {
                    $query->where('name', 'like', '%'.$search_text.'%');
                });
            /*Log::info('+++'.Pupils::where('firstName', 'like', '%'.$search_text.'%')->orWhere('secondName', 'like', '%'.$search_text.'%')->exists());
            $pupils_count = Pupils::where('firstName', 'like', '%'.$search_text.'%')
                ->orWhere('secondName', 'like', '%'.$search_text.'%')->count();*/
            if($filters['chooser'] == 2)
                $query -> whereHas('pupils', function ($query) use($search_text) {
                    $query->where('firstName', 'like', '%'.$search_text.'%');
                    $query->orWhere('secondName', 'like', '%'.$search_text.'%');

                });
        }
        /*if($filters['pupil_id']){
            Log::info("pupil id is working");
            $pupil_id = $filters['pupil_id'];
            $query -> whereHas('pupils', function ($query) use($pupil_id) {

                $query->Where('pupils_id', '=', $pupil_id);

            });
        }*/

        return $query;
    }
}
