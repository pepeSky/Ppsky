<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

     //** Relación uno a muchos inversa **//

     public function user(){
        return $this->belongsTo('App\Model\User');
     }

     public function branch(){
        return $this->belongsTo('App\Model\Branch');
     }
}
