<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function sendPasswordResetNotification($token)
    {
        if (env('APP_ENV') == 'local')
        {
            $url = 'http://127.0.0.1:8000/#/password/reset?token='.$token;
        }
        else if (env('APP_ENV') == 'production')
        {
            $url = 'http://eco-laravue.herokuapp.com/#/password/reset?token='.$token;
        }

        $this->notify(new ResetPasswordNotification($url));
    }
}
