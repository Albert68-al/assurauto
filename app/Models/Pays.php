<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'code',
        'devise',
        'tva_par_defaut',
        'actif',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array
     */
    protected $casts = [
        'tva_par_defaut' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /**
     * Les valeurs par défaut des attributs du modèle.
     *
     * @var array
     */
    protected $attributes = [
        'actif' => true,
        'tva_par_defaut' => 0,
    ];

    /**
     * Relation avec les produits
     */
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
