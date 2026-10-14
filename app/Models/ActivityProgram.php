<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityProgram extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['scheduled_on' => 'date'];
    public function setScheduledOnAttribute($value): void
    {
        $this->attributes['scheduled_on'] = \Carbon\CarbonImmutable::parse($value)->toDateString();
    }

    public function activity() { return $this->belongsTo(Activity::class); }
}
