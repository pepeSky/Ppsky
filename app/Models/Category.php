<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getRoutekeyName(){
        return 'slug';
    }


    //** Relación Polimorfica */
        public function categoryable(){
            return $this->MorphTo();
        }


    //** Relación uno a muchos **//
        public function development(){
        return $this->hasMany('App\Models\Development');
    }


    //** Relación uno a muchos inversa **//
        public function sector(){
            return $this->belongsTo('App\Models\Sector');
}

}
