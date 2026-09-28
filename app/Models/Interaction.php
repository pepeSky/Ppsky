<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actorA()
    {
        return $this->belongsTo(Actor::class, 'actor_a_id');
    }

    public function actorB()
    {
        return $this->belongsTo(Actor::class, 'actor_b_id');
    }

    public function cohesion()
    {
        return $this->belongsTo(Cohesion::class);
    }
}
