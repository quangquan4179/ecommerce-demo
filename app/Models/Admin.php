<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

use Illuminate\Notifications\Notifiable;

class Admin extends  Authenticatable implements JWTSubject
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'active',
        'address',
        'total_pay',
    ];
    public function transaction(){
        return $this->hasMany(Transaction::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
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
}
