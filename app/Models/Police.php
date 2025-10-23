<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Police extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'polices';
    // Allow mass assignment for these fields
    protected $fillable = [
        'vehicule_id',
        'produit_id',
        'numero_police',
        'date_debut',
        'date_fin',
        'montant_prime',
        'statut',
        'garanties',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];


    /**
     * Relationship: A police belongs to a vehicle
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }
}
