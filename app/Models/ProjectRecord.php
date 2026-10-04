<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class)
            ->withTimestamps();
    }
}
