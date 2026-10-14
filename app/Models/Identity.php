<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['personal_information'=>'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if ($this->person_type==='legal' && filled($this->legal_name)) { return trim($this->legal_name); }
        $parts=array_filter([$this->first_name,$this->second_name,$this->paternal_surname,$this->maternal_surname],fn($part)=>filled($part));
        return $parts ? implode(' ',array_map('trim',$parts)) : ($this->legal_name ?: ($this->entity?->name ?? ''));
    }
    public function actors() { return $this->hasMany(Actor::class); }

    // Compatibility for existing consumers; new features use actors().
    public function actor()
    {
        return $this->hasOne(Actor::class);
    }

    public function system()
    {
        return $this->hasOne(System::class);
    }
}
