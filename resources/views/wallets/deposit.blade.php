@extends('layouts.app')

@section('content')
<div class="wallet-container">

    <div class="wallet-header">
        <div>
            <h2><span class="icon">💰</span> Deposit to Wallet</h2>
            <p class="subtitle">Add funds securely to a user’s wallet</p>
        </div>
        <a href="{{ route('wallets.index') }}" class="btn btn-secondary">
            ⬅ Back to Wallets
        </a>
    </div>

    <div class="card wallet-card">
        <div class="card-header bg-success">💸 Deposit Form</div>
        <div class="card-body">
            <form action="{{ route('wallets.deposit') }}" method="POST" class="form">
                @csrf
                <div class="form-group">
                    <label for="wallet_id">Select Wallet</label>
                    <select name="wallet_id" id="wallet_id" required>
                        <option value="">-- Choose Wallet --</option>
                        @foreach ($wallets as $wallet)
                            <option value="{{ $wallet->id }}">
                                {{ $wallet->user->name }} — $ {{ number_format($wallet->solde, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" step="0.01" placeholder="Enter amount" required>
                </div>

                <button type="submit" class="btn btn-success full-width mt-2">
                    Deposit Funds
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .wallet-container {
        padding: 30px;
        color: #333;
        font-family: "Segoe UI", sans-serif;
    }

    .wallet-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .wallet-header h2 {
        margin: 0;
        font-size: 1.8rem;
        color: #0056b3;
    }

    .wallet-header .subtitle {
        margin-top: 4px;
        color: #666;
        font-size: 0.95rem;
    }

    .wallet-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }

    .card-header {
        padding: 12px 16px;
        font-weight: 600;
        color: #fff;
    }

    .bg-success { background-color: #28a745; }
    .btn-secondary {
        background-color: #0056b3;
        color: #fff;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
    }

    .btn-secondary:hover {
        background-color: #054080ff;
    }

    .card-body {
        padding: 20px;
    }

    .form {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    select, input {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    select:focus, input:focus {
        border-color: #0056b3;
        outline: none;
        box-shadow: 0 0 3px rgba(0, 116, 217, 0.3);
    }

    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-success {
        background-color: #28a745;
        color: #fff;
        box-shadow: 0 3px 6px rgba(46, 204, 113, 0.3);
    }

    .btn-success:hover {
        background-color: #28a745;
    }

    .full-width { width: 100%; }
    .mt-2 { margin-top: 12px; }

    @media (max-width: 768px) {
        .wallet-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .wallet-card {
            width: 100%;
        }
    }
</style>
@endpush
