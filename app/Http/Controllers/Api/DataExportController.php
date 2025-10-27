<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DataExportController extends Controller
{
    public function index()
    {
        // Eager load all relationships to avoid N+1 problem
        $users = User::with('vehicules.polices.produit')->get();

        // Transform the data into the required format
        $data = $users->flatMap(function ($user) {
            return $user->vehicules->flatMap(function ($vehicule) use ($user) {
                return $vehicule->polices->map(function ($police) use ($vehicule, $user) {
                    return [
                        'nom' => $police->produit->nom ?? null,
                        'numero_police' => $police->numero_police,
                        'immatriculation' => $vehicule->immatriculation,
                        'couverture' => $police->produit->couverture ?? null,
                        'date_debut' => $police->date_debut,
                        'date_fin' => $police->date_fin,
                        'tarif_base' => $police->produit->tarif_base ?? null,
                        'name' => $user->name,
                        'email' => $user->email,
                        'address' => $user->address,
                        'id_number' => $user->id_number,
                        'marque' => $vehicule->marque,
                        'modele' => $vehicule->modele,
                        'annee_vehicule' => $vehicule->annee_vehicule,
                        'usage_vehicule' => $vehicule->usage_vehicule,
                        'vehicule_photo' => $vehicule->vehicule_photo,
                        'numero_chassis' => $vehicule->numero_chassis,
                    ];
                });
            });
        });

        return response()->json($data);
    }
}
