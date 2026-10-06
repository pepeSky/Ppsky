<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetive extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_general' => 'boolean',
        ];
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function parent()
    {
        return $this->belongsTo(Objetive::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Objetive::class, 'parent_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class);
    }
}
