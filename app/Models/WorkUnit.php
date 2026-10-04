<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkUnit extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Sistema desde cuya perspectiva se define
     * esta unidad de trabajo.
     */
    public function system()
    {
        return $this->belongsTo(System::class);
    }

    /**
     * Actor que constituye la unidad de trabajo.
     */
    public function actor()
    {
        return $this->belongsTo(Actor::class);
    }

    /**
     * Unidad SyQuAc utilizada:
     * UnNE, UnSe, UnSo, UnOp o UnEl.
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
