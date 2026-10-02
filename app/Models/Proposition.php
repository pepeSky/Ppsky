<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }
}
