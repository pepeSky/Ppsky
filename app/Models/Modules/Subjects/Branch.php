<?php

namespace App\Models\Modules\Subjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sector;

class Branch extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    //** Relación uno a muchos **//
    public function sectors(){
        return $this->hasMany(Sector::class);
    }

    public function units(){
        return $this->hasMany('App\Models\Unit');
    }


    //** Relación uno a muchos inversa **//
     public function science(){
        return $this->belongsTo('App\Models\Science');
     }

}
