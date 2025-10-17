<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Pays;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Produit::with('pays')
            ->when($request->pays, function($q) use ($request) {
                return $q->where('pays_id', $request->pays);
            })
            ->when($request->actif !== null, function($q) use ($request) {
                return $q->where('actif', $request->boolean('actif'));
            });

        $produits = $query->latest()->paginate(10);
        $pays = Pays::where('actif', true)->get();

        return view('produits.index', compact('produits', 'pays'));
    }

    /**
     * Afficher le formulaire de création d'un produit
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $pays = Pays::where('actif', true)->get();
        $devises = $this->getDevisesDisponibles();
        
        return view('produits.create', compact('pays', 'devises'));
    }

    /**
     * Enregistrer un nouveau produit
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'couverture' => 'required|string',
            'tarif_base' => 'required|numeric|min:0',
            'taux' => 'nullable|numeric|min:0|max:100',
            'tva' => 'nullable|numeric|min:0|max:100',
            'devise' => 'required|string|size:3',
            'pays_id' => 'required|exists:pays,id',
            'actif' => 'boolean',
        ]);

        // Utiliser la TVA par défaut du pays si non spécifiée
        if (!isset($validated['tva'])) {
            $pays = Pays::find($validated['pays_id']);
            $validated['tva'] = $pays->tva_par_defaut;
        }

        Produit::create($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher les détails d'un produit
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\View\View
     */
    public function show(Produit $produit)
    {
        $produit->load('pays');
        return view('produits.show', compact('produit'));
    }

    /**
     * Afficher le formulaire d'édition d'un produit
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\View\View
     */
    public function edit(Produit $produit)
    {
        $pays = Pays::actif()->get();
        $devises = $this->getDevisesDisponibles();
        
        return view('produits.edit', compact('produit', 'pays', 'devises'));
    }

    /**
     * Mettre à jour un produit
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'couverture' => 'required|string',
            'tarif_base' => 'required|numeric|min:0',
            'taux' => 'nullable|numeric|min:0|max:100',
            'tva' => 'nullable|numeric|min:0|max:100',
            'devise' => 'required|string|size:3',
            'pays_id' => 'required|exists:pays,id',
            'actif' => 'boolean',
        ]);

        // Utiliser la TVA par défaut du pays si non spécifiée
        if (!isset($validated['tva'])) {
            $pays = Pays::find($validated['pays_id']);
            $validated['tva'] = $pays->tva_par_defaut;
        }

        $produit->update($validated);

        return redirect()->route('produits.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Changer le statut d'activation d'un produit
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Produit $produit)
    {
        $produit->update(['actif' => !$produit->actif]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut du produit mis à jour avec succès',
            'actif' => $produit->actif
        ]);
    }

    /**
     * Supprimer un produit
     *
     * @param  \App\Models\Produit  $produit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')
            ->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Obtenir la liste des devises disponibles
     *
     * @return array
     */
    protected function getDevisesDisponibles()
    {
        return [
            'USD' => 'Dollar américain (USD)',
            'EUR' => 'Euro (EUR)',
            'XAF' => 'Franc CFA (XAF)',
            'CDF' => 'Franc congolais (CDF)',
            // Ajoutez d'autres devises selon les besoins
        ];
    }
}
