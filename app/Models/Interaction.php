<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Interaction extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (Interaction $interaction) {

            if ($interaction->actor_a_id === $interaction->actor_b_id) {
                throw new InvalidArgumentException(
                    'Una interacción requiere dos actores distintos.'
                );
            }

            if ($interaction->actor_a_id > $interaction->actor_b_id) {
                [$interaction->actor_a_id, $interaction->actor_b_id] = [
                    $interaction->actor_b_id,
                    $interaction->actor_a_id,
                ];
            }
        });
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
