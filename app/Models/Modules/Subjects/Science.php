<?php

namespace App\Models\Modules\Subjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;

class Science extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //** Relación uno a muchos**//

    public function branches(){
        return $this->hasMany(Branch::class);
    }
}
