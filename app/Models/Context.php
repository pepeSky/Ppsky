<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function mewoProcesses()
    {
         return $this->hasMany(MewoProcess::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'context_id');
    }

    public function components()
    {
        return $this->belongsToMany(self::class, 'context_components', 'context_id', 'component_id')
            ->withPivot('position')->orderByPivot('position');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }
}
