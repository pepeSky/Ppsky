<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Context extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function mewoProcesses()
    {
         return $this->hasMany(MewoProcess::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'context_id');
    }
}
