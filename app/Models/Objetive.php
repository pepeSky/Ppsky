<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relación 1:N Inversa
    public function goal(){
        return $this->belongsTo('App\Models\Goal');
    }

    // Relación 1:N Inversa
    public function Requirement(){
        return $this->belongsTo('App\Models\Requirement');
    }
}
