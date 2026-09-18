<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const ERASER = 1;
    const PUBLISHED = 2;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    //** Relación uno a muchos inversa **//

    public function user(){
        return $this->belongsTo('App\Models\User');
    }


    //** Relación muchos a muchos  **//
    public function tags(){
        return $this->belongsToMany('App\Models\Tag');
    }


    //** Relación uno a uno polimorfica  **//
    public function image(){
        return $this->morphOne('App\Models\Image','imageable');
    }


     //** Relación uno a muchos polimorfica  **//
     public function categories(){
        return $this->morphMany('App\Models\Category','categoryable');
    }

}
