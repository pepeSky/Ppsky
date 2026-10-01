<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
