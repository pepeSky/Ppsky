<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    //** Relación uno a uno **//
    public function profile(){
       return $this->hasOne('App\Models\Profile');
    }

    //** Relación uno a muchos **//
    public function developments_clients(){
        return $this->hasMany('App\Models\Development');
    }

    public function reviews(){
        return $this->hasMany('App\Models\Review');
    }

    public function units(){
        return $this->hasMany('App\Models\Unit');
    }

    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }

    public function reactions(){
        return $this->hasMany('App\Models\Reaction');
    }

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }


    //** Relación muchos a muchos **//
    public function developments_enrolled(){
        return $this->belongsToMany('App\Models\Development');
    }

    public function processes(){
        return $this->belongsToMany('App\Models\Process');
    }


}
