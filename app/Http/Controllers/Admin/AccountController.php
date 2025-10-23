<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show the wallet management page.
     */
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['admin', 'super_admin']);
        })->get();
        $wallets = Wallet::with('user')->get();

        return view('wallets.index', compact('users', 'wallets'));
    }

    /**
     * Create a wallet for a user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:wallets,user_id',
        ]);

        Wallet::create([
            'user_id' => $request->user_id,
            'solde' => 0,
        ]);

        return redirect()->back()->with('success', 'Wallet created successfully!');
    }

    public function depositForm()
    {
        $wallets = Wallet::with('user')->get();

        return view('wallets.deposit', compact('wallets'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Find the wallet
        $wallet = Wallet::findOrFail($request->wallet_id);

        // Increment wallet balance
        $wallet->deposit($request->amount);

        // Create a transaction automatically
        $wallet->transactions()->create([
            'type' => 'debit',
            'amount' => $request->amount,
            'description' => 'Dépôt administratif', // optional, can be customized
        ]);

        return redirect()->back()->with('success', 'Dépôt réussi!');
    }
}
