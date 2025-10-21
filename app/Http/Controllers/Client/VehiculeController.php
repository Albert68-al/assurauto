<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicule;
use Illuminate\Support\Facades\Auth;

class VehiculeController extends Controller
{
    public function index()
    {
        $vehicules = Vehicule::where('user_id', Auth::id())->latest()->get();
        return view('client.vehicules.index', compact('vehicules'));
    }

    public function create() 
    {
        return view('client.vehicules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'immatriculation' => 'required|unique:vehicules',
            'marque' => 'required',
            'modele' => 'required',
            'annee_vehicule' => 'required|integer',
            'usage_vehicule' => 'required',
            'numero_chassis' => 'required|unique:vehicules',
            'vehicule_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        $filePath = null;

        if ($request->hasFile('vehicule_photo')) {
            $file = $request->file('vehicule_photo');
            $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('uploads', $fileName, 'public');
            $filePath = 'storage/uploads/' . $fileName;
        }


        Vehicule::create([
            'user_id' => auth()->id(),
            'immatriculation' => $request->immatriculation,
            'marque' => $request->marque,
            'modele' => $request->modele,
            'annee_vehicule' => $request->annee_vehicule,
            'usage_vehicule' => $request->usage_vehicule,
            'numero_chassis' => $request->numero_chassis,
            'vehicule_photo' => $filePath,
        ]);

        return redirect()->route('client.vehicules.index')->with('success', 'Véhicule ajouté avec succès.');
    }

    public function edit(Vehicule $vehicule)
    {
        $this->authorize('update', $vehicule);
        return view('client.vehicules.edit', compact('vehicule'));
    }

    // Handle the form submission
    public function update(Request $request, Vehicule $vehicule)
    {
        $this->authorize('update', $vehicule); 

        $validated = $request->validate([
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation,' . $vehicule->id,
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee_vehicule' => 'required|integer',
            'usage_vehicule' => 'required|string|max:255',
            'numero_chassis' => 'required|string|max:255|unique:vehicules,numero_chassis,' . $vehicule->id,
            'vehicule_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10048',
        ]);

        if ($request->hasFile('vehicule_photo')) {
            if ($vehicule->vehicule_photo && file_exists(public_path($vehicule->vehicule_photo))) {
                unlink(public_path($vehicule->vehicule_photo));
            }

            $file = $request->file('vehicule_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);

            $validated['vehicule_photo'] = 'uploads/' . $fileName;
        }

        $vehicule->update($validated);

        return redirect()->route('client.vehicules.index')->with('success', 'Véhicule mis à jour avec succès ✨');
    }

    public function destroy(Vehicule $vehicule)
    {
        $this->authorize('delete', $vehicule);

        if ($vehicule->vehicule_photo && file_exists(public_path($vehicule->vehicule_photo))) {
            unlink(public_path($vehicule->vehicule_photo));
        }

        $vehicule->delete();

        return redirect()->route('client.vehicules.index')->with('success', 'Véhicule supprimé ✅');
    }

}
