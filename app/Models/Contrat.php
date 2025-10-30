<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contrat extends Model
{
    protected $fillable = [
        'user_id',
        'numero_contrat',
        'date_debut',
        'date_fin',
        'statut',
        'montant',
        'details'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
