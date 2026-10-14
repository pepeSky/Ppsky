<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Application extends EloquentModel
{
    protected $guarded = ['id'];

    protected $casts = ['compatible_platforms' => 'array'];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function models()
    {
        return $this->modelAssociations()->wherePivot('is_active', true);
    }

    public function modelAssociations()
    {
        return $this->belongsToMany(\App\Models\Model::class)->withPivot('is_active')->withTimestamps();
    }

    public function associateModels(array $ids): array
    {
        $ids = array_values(array_unique(array_map('intval', $ids)));
        $active = $this->models()->whereIn('models.id', $ids)->pluck('models.id')->all();
        $changes = $this->modelAssociations()->syncWithoutDetaching(array_fill_keys($ids, ['is_active' => true]));
        $changes['attached'] = array_values(array_diff($ids, $active));
        return $changes;
    }
}
