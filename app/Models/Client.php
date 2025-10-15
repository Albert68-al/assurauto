<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'pays',
        'code_postal',
        'date_naissance',
        'numero_permis',
    ];

    protected $dates = [
        'date_naissance',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function polices()
    {
        return $this->hasMany(Police::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }
}
