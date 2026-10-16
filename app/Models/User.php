<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function citizenReports(): HasMany
    {
        return $this->hasMany(CitizenReport::class);
    }

    public function sentShipments(): HasMany
    {
        return $this->hasMany(
            Shipment::class,
            'sender_user_id'
        );
    }

    public function receivedShipments(): HasMany
    {
        return $this->hasMany(
            Shipment::class,
            'receiver_user_id'
        );
    }
}
