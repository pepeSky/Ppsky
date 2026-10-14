<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ActorClassification extends Model
{
    protected $guarded=['id'];
    public function actor() { return $this->belongsTo(Actor::class); }
    public function cohesion() { return $this->belongsTo(Cohesion::class); }
    public function children() { return $this->belongsToMany(self::class,'actor_classification_links','parent_id','child_id')->withTimestamps(); }
    public function parents() { return $this->belongsToMany(self::class,'actor_classification_links','child_id','parent_id')->withTimestamps(); }
    public function getTypeNameAttribute() { return config('actors.types.'.$this->unit_symbol.'.'.$this->type_position,'Sin clasificar'); }
}
