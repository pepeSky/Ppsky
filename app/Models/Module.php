<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    //** Relación uno a inversa **//
    public function development(){
        return $this->belongsTo('App\Models\Development');
    }

    //** Relación uno a muchos **//

    public function processes(){
        return $this->hasMany('App\Models\Process');

    }

    public function getRouteKeyName(){
        return "slug";
    }
}
