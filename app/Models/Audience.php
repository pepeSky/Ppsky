<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audience extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relación uno a muchos Inversa
    public function development(){
        return $this->belongsTo('App\Models\Development');
    }
}
