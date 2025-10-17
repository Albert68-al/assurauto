<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nationality',
        'id_number',
        'phone_number',
        'address',
        'city',
        'postal_code',
    ];

    protected $attributes = [
    'nationality' => 'Non spécifiée',
    'id_number' => 'N/A',
    'phone_number' => 'N/A',
    'address' => 'Non spécifiée',
    'city' => 'Non spécifiée',
    'postal_code' => '00000',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Permissions pour superadmin
     public function isSuperAdmin()
    {
        return $this->hasRole('super_admin');
    }
    
    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }
}