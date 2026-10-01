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
}
