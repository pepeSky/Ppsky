<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SystemRole extends Model {
    protected $guarded = ['id'];
    protected $casts = ['permissions'=>'array'];
    public function users() { return $this->belongsToMany(User::class, 'system_role_user')->withTimestamps(); }
}
