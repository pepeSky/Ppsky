<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['archived_at'=>'datetime'];
    public function classifications() { return $this->hasMany(ActorClassification::class)->orderBy('id'); }
    public function getDisplayNameAttribute(): string { return filled($this->name) ? trim($this->name) : $this->identity->display_name; }
    public function unit() { return $this->belongsTo(Unit::class); }
    public function system() { return $this->belongsTo(System::class); }
    public function originActor() { return $this->belongsTo(self::class, 'origin_actor_id'); }
    public function cohesions() { return $this->belongsToMany(Cohesion::class, 'actor_cohesion')->withTimestamps(); }
    public function children() { return $this->belongsToMany(self::class, 'actor_links', 'parent_id', 'child_id')->withTimestamps(); }
    public function parents() { return $this->belongsToMany(self::class, 'actor_links', 'child_id', 'parent_id')->withTimestamps(); }
    public function getTypeNameAttribute() { return config('actors.types.'.$this->unit_symbol.'.'.$this->type_position, 'Sin clasificar'); }


    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    public function interactionsAsA()
    {
        return $this->hasMany(Interaction::class, 'actor_a_id');
    }

    public function interactionsAsB()
    {
        return $this->hasMany(Interaction::class, 'actor_b_id');
    }

    public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }
}
