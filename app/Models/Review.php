<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relación 1:N Inversa
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function development(){
        return $this->belongsTo('App\Models\Development');
    }

}
