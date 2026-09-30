<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MewoProcess extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function context()
    {
        return $this->belongsTo(Context::class);
    }
}
