<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relación 1:1 Inversa

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
