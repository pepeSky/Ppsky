<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $guarded = ['id'];


    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    public function interactionsAsA()
    {
        return $this->hasMany(Interaction::class, 'actor_a_id');
    }

    public function interactionsAsB()
    {
        return $this->hasMany(Interaction::class, 'actor_b_id');
    }
}
