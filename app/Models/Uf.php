<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uf extends Model
{
    protected $fillable = [
        'fecha',
        'valor',
        'fuente',
        'codigo',
        'consultado_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
        'consultado_at' => 'datetime',
    ];
}
