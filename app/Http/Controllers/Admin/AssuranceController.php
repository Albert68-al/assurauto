<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Police;
use App\Models\Produit;
use Carbon\Carbon;

class AssuranceController extends Controller
{
    public function index(Request $request)
    {
        $query = Police::query();

        // Example filters:
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('numero_police')) {
            $query->where('numero_police', 'like', '%' . $request->numero_police . '%');
        }

        $polices = $query->paginate(10);

        $users = \App\Models\User::all(); // Needed for your <select>
        $statuts = [
            'En attente' => 'En attente',
            'Active' => 'Active',
            'Expiré' => 'Expiré',
        ];

        return view('admin.polices.index', compact('polices', 'users', 'statuts'));
    }


    // Approve a policy
    public function approve($id)
    {
        $policy = Police::findOrFail($id);

        if ($policy->statut !== 'En attente') {
            return redirect()->back()->with('error', 'Policy is not pending approval.');
        }

        // Change statut to Active
        $policy->statut = 'Active';

        // Get the product associated with the policy
        $produit = Produit::findOrFail($policy->produit_id);

        // Calculate date_debut and date_fin based on duree (string)
        $policy->date_debut = Carbon::now();
        $policy->date_fin = $this->calculateEndDate($policy->date_debut, $produit->duree);

        $policy->save();

        return redirect()->back()->with('success', 'Police approuvée et dates mises à jour avec succès.');
    }


    private function calculateEndDate($startDate, $duree)
    {
        $start = Carbon::parse($startDate);

        // Convert duree string to integer
        $months = (int) $duree;

        // Add months to start date
        return $start->addMonths($months);
    }
    
}
