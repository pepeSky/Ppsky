<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ApplicationModelRelation extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];
    public function source() { return $this->belongsTo(\App\Models\Model::class, 'source_model_id'); }
    public function target() { return $this->belongsTo(\App\Models\Model::class, 'target_model_id'); }
}
