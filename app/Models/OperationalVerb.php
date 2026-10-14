<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationalVerb extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['forms' => 'array'];

    public function processes()
    {
        return $this->belongsToMany(MewoProcess::class, 'mewo_process_operational_verb');
    }
}
