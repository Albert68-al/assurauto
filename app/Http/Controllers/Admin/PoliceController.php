<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Police;
use Illuminate\Http\Request;

class PoliceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polices = Police::with(['vehicule.user', 'produit'])
            ->latest()
            ->paginate(10);

        $statuts = [
            'En attente' => 'En attente',
            'Active' => 'Active',
            'Expiré' => 'Expiré'
        ];

        $users = \App\Models\User::role('client')->get();

        return view('admin.polices.index', compact('polices', 'statuts', 'users'));
    }

    /**
     * Approve the specified police.
     */
    public function approve(Police $police)
    {
        // Vérifier si la police est bien en attente
        if ($police->statut !== 'En attente') {
            return redirect()->route('admin.polices.index')
                ->with('error', 'Seules les polices en attente peuvent être approuvées.');
        }

        // Mettre à jour le statut de la police
        $police->update([
            'statut' => 'Active',
            'date_debut' => now(),
            'date_fin' => now()->addYear() // Par défaut, la police est valable un an
        ]);

        return redirect()->route('admin.polices.index')
            ->with('success', 'La police a été approuvée avec succès.');
    }

    // Les autres méthodes du contrôleur (create, store, show, edit, update, destroy) peuvent être ajoutées ici
    // selon les besoins de votre application
}
