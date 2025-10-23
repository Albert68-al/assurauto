<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Police;
use App\Models\Vehicule;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;

class PoliceController extends Controller
{
    public function index()
    {
        $polices = Police::whereHas('vehicule', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->latest()
        ->get();

        return view('client.polices.index', compact('polices'));
    }

    public function create()
    {
        $vehicules = Vehicule::where('user_id', Auth::id())->get();
        $produits = Produit::all();
        return view('client.polices.create', compact('vehicules', 'produits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'produit_id' => 'required|exists:produits,id',
            'numero_police' => 'required|unique:polices',
        ]);

        $vehicule = Vehicule::where('id', $request->vehicule_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $existingPolice = Police::where('vehicule_id', $vehicule->id)
            ->whereIn('statut', ['En attente', 'Active'])
            ->first();

        if($existingPolice)
        {
            return back()->withErrors([
                'vehicule_id' => 'Ce véhicule a déjà une police en attente ou active. Vous ne pouvez pas en créer une nouvelle tant qu\'elle n\'est pas expirée.'
            ])->withInput();
        }

        $police = Police::create(array_merge(
            $request->all(),
            ['statut' => 'En attente']
        ));

        return redirect()->route('client.polices.index')->with('success', 'Police créée avec succès');
    }


    public function edit(Police $police)
    {
        // Restrict access to only the user's vehicles
        $vehicules = Vehicule::where('user_id', Auth::id())->get();

        // Optional: make sure the police belongs to the user's vehicle
        if ($police->vehicule->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('client.polices.edit', compact('police', 'vehicules'));
    }
    
    public function update(Request $request, Police $police)
    {
        if ($police->statut !== 'En attente') {
            return redirect()->route('client.polices.index')
                ->with('error', 'Cette police ne peut plus être modifiée car elle n\'est plus en attente.');
        }

        if ($police->vehicule->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $request->validate([
            'vehicule_id' => 'required|exists:vehicules,id',
            'numero_police' => 'required|unique:polices,numero_police,' . $police->id,
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'montant_prime' => 'required|numeric',
        ]);

        $vehicule = Vehicule::where('id', $request->vehicule_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $police->update($request->all());

        return redirect()->route('client.polices.index')->with('success', 'Police mise à jour avec succès');
    }

    public function destroy(Police $police)
    {
        if ($police->statut !== 'En attente') {
            return redirect()->route('client.polices.index')
                ->with('error', 'Cette police ne peut plus être supprimée car elle n\'est plus en attente.');
        }

        if ($police->vehicule->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $police->delete();
        return redirect()->route('client.polices.index')->with('success', 'Police supprimée avec succès');
    }

}
