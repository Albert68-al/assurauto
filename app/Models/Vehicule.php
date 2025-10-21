<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'immatriculation',
        'marque',
        'modele',
        'annee_vehicule',
        'usage_vehicule',
        'numero_chassis',
        'vehicule_photo',
    ];

    /**
     * Get the client (user) that owns the vehicle.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function polices()
    {
        return $this->hasMany(Police::class);
    }
}
