<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    use HasFactory;

    protected $guarded = ['id','status'];
    protected $withCount = ['users', 'reviews'];

    const ERASER = 1;
    const REVISION = 2;
    const PUBLISHED = 3;



    public function getRatingAttribute(){

        if($this->reviews_count){
            return round($this->reviews->avg('rating'), 1);
        }else{
            return 5;
        }
    }



    // Relación 1:N
    public function reviews(){
        return $this->hasMany('App\Models\Review');
    }

    public function requirements(){
        return $this->hasMany('App\Models\Requirement');
    }

    public function goals(){
        return $this->hasMany('App\Models\Goal');
    }

    public function audiences(){
        return $this->hasMany('App\Models\Audience');
    }

    public function modules(){
        return $this->hasMany('App\Models\Module');
    }


    // Relación 1:N Inversa
    public function client(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function level(){
        return $this->belongsTo('App\Models\Level');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category');
    }

    public function price(){
        return $this->belongsTo('App\Models\Price');
    }


    //Relacion N:N
    public function users(){
        return $this->belongsToMany('App\Models\User');
    }

    //** Relación uno a uno polimorfica **//
    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }

     //** Relación by-pass **//
     public function process(){
        return $this->hasManyThrough('App\Models\Process', 'App\Models\Module');
    }

}
