<?php

namespace App\Models;

use App\Models\Order;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'name', 'email', 'password', 'img',
        'job_title'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $appends = ['img_path'];

    public function getImgPathAttribute()
    {
        return asset("uploads/employees/" . $this->attributes['img']);
    }
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
