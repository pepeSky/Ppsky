<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }
}
