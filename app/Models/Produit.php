<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'description',
        'couverture',
        'tarif_base',
        'duree',
        'taux',
        'tva',
        'devise',
        'pays_id',
        'actif',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array
     */
    protected $casts = [
        'tarif_base' => 'decimal:2',
        'taux' => 'decimal:2',
        'tva' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /**
     * Les valeurs par défaut des attributs du modèle.
     *
     * @var array
     */
    protected $attributes = [
        'actif' => true,
        'taux' => 0,
        'tva' => 0,
        'devise' => 'USD',
        'duree' => '12',
    ];

    /**
     * Relation avec le pays
     */
    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    /**
     * Calcul du tarif TTC
     *
     * @return float
     */
    public function getTarifTtcAttribute()
    {
        return $this->tarif_base * (1 + ($this->taux / 100)) * (1 + ($this->tva / 100));
    }

    /**
     * Scope pour les produits actifs
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les produits par pays
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $paysId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParPays($query, $paysId)
    {
        return $query->where('pays_id', $paysId);
    }
}
