<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

     // Relación 1:N Inversa
     public function development(){
        return $this->belongsTo('App\Models\Development');
    }

    // Relación 1:N
    public function objetives(){
        return $this->hasMany('App\Models\Objetive');
    }
}
