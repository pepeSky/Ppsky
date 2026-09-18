<?php

namespace App\Models\Modules\Subjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

        //** Relación uno a muchos **//
        public function categories(){
            return $this->hasMany('App\Models\Category');
        }


        //** Relación uno a muchos inversa **//
         public function branch(){
            return $this->belongsTo('App\Models\Branch');
         }

}
