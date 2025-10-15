<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Police extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'numero_police',
        'date_effet',
        'date_echeance',
        'montant_prime',
        'statut',
        'type_vehicule',
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'type_couverture',
        'garanties',
        'franchises',
    ];

    protected $dates = [
        'date_effet',
        'date_echeance',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'garanties' => 'array',
        'franchises' => 'array',
        'montant_prime' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sinistres()
    {
        return $this->hasMany(Sinistre::class);
    }

    public function getPeriodeAttribute()
    {
        return $this->date_effet->format('d/m/Y') . ' - ' . $this->date_echeance->format('d/m/Y');
    }
}
