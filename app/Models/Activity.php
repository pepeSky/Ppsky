<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function objetives()
    {
        return $this->belongsToMany(Objetive::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function contexts()
    {
        return $this->belongsToMany(Context::class);
    }

    public function mewoProcesses()
    {
        return $this->belongsToMany(MewoProcess::class, 'activity_mewo_process');
    }

    // Aggregate tasks once, never by joins to contexts or processes.
    public function quantities()
    {
        return $this->tasks()->whereNotNull('unit_id')->selectRaw('unit_id, SUM(quantity) as total')
            ->groupBy('unit_id')->with('unit')->get();
    }
}
