<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }

    public function objetives()
    {
        return $this->hasMany(Objetive::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
