<?php

namespace App\Models\Modules\Subjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    const APPROVED = 1;
    const STUDYING = 2;
    const FINALIZED = 3;

    use HasFactory;

    protected $guarded = ['id'];


    //Query Scopes
    public function scopeScience($query, $science_id){

        if($science_id){
            return $query->where('science_id', $science_id);
        }
        return ;
    }

    public function scopeLevel($query, $level_id){

        if($level_id){
            return $query->where('level_id', $level_id);
        }
        return ;
    }

}
