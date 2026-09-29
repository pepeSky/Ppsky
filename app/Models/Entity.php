<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nature()
    {
        return $this->belongsTo(Nature::class);
    }
    
    public function types()
    {
        return $this->belongsToMany(Type::class)
            ->withTimestamps();
    }
    
    public function identities()
    {
        return $this->hasMany(Identity::class);
    }
}
