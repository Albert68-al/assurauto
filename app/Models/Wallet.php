<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'solde',
    ];

    // Relationship: A wallet belongs to a user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Method to deposit
    public function deposit($amount)
    {
        $this->increment('solde', $amount);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
