<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteractionPeriod extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime'];
    public function interaction() { return $this->belongsTo(Interaction::class); }
}
