<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sinistre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'police_id',
        'numero_sinistre',
        'date_sinistre',
        'lieu',
        'description',
        'statut',
        'montant_dommages',
        'circonstances',
        'tiers_implique',
        'tiers_assureur',
        'tiers_immatriculation',
        'temoignages',
        'constat_amiable',
        'expertise',
        'date_expertise',
        'montant_indemnisation',
        'date_indemnisation',
        'commentaires',
    ];

    protected $dates = [
        'date_sinistre',
        'date_expertise',
        'date_indemnisation',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'montant_dommages' => 'decimal:2',
        'montant_indemnisation' => 'decimal:2',
        'temoignages' => 'array',
    ];

    public function police()
    {
        return $this->belongsTo(Police::class);
    }

    public function getClientAttribute()
    {
        return $this->police->client ?? null;
    }
}
