<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function actor()
    {
        return $this->hasOne(Actor::class);
    }

    public function system()
    {
        return $this->hasOne(System::class);
    }
}
