<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    protected $guarded = ['id'];

    public function model()
    {
        return $this->belongsTo(\App\Models\Model::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
