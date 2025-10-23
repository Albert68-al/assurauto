<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Produit;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::User();

        $stats = [
            'vehicules' => $user->vehicules()->count(),
            'polices' => $user->vehicules()
                                ->withCount('polices')
                                ->get()
                                ->sum('polices_count'),
            'wallet' => $user->wallet->solde ?? 0,
        ];

        $produits = Produit::with('pays')
                        ->where('actif', true)
                        ->get();

        return view('client.dashboard.index', compact('stats', 'produits'));
    }
}
