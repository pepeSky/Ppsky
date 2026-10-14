<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Binnacle extends EloquentModel
{
    protected $guarded = ['id'];
    protected $casts = ['recorded_on' => 'date'];

    public function system() { return $this->belongsTo(System::class); }
    public function project() { return $this->belongsTo(Project::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function propositions() { return $this->belongsToMany(Proposition::class); }
    public function models() { return $this->belongsToMany(Model::class); }
    public function activities() { return $this->belongsToMany(Activity::class, 'binnacle_activity'); }
}
