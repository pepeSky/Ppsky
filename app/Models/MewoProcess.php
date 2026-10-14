<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MewoProcess extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function context()
    {
        return $this->belongsTo(Context::class);
    }

    public function verbs()
    {
        return $this->belongsToMany(OperationalVerb::class, 'mewo_process_operational_verb');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_mewo_process');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
