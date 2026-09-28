<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Application extends EloquentModel
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function models()
    {
        return $this->belongsToMany(\App\Models\Model::class);
    }
}
