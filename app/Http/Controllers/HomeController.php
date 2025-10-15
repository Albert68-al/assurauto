<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Police;
use App\Models\Sinistre;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'clients' => Client::count(),
            'polices' => Police::count(),
            'sinistres' => Sinistre::count(),
            'revenue' => Police::sum('montant_prime')
        ];

        return view('dashboard.index', compact('stats'));
    }
}