<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    protected $guarded = ['id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function context()
    {
        return $this->belongsTo(Context::class);
    }
}
