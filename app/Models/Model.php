<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function definitions()
    {
        return $this->hasMany(Definition::class);
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class);
    }
}
