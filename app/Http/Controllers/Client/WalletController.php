<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function transactions()
    {
        $user = auth()->user();

        // Load the wallet with its transactions safely
        $wallet = $user->wallet()->with('transactions')->first();

        if (!$wallet) {
            // Option 1: show empty page
            return view('client.wallet-transaction', ['wallet' => null]);

            // Option 2: create wallet automatically
            // $wallet = $user->wallet()->create(['solde' => 0]);
        }

        return view('client.wallet-transaction', compact('wallet'));
    }
}
