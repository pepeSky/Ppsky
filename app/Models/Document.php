<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $guarded = ['id'];

    //** Relación muchos a uno **//
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class)
                    ->withTimestamps();
    }

    public function projectRecords()
    {
        return $this->belongsToMany(ProjectRecord::class)
                    ->withTimestamps();
    }
}
