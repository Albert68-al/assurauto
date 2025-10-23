@extends('layouts.app')

@section('content')
<div class="wallet-container">

    <div class="wallet-header">
        <div>
            <h2><span class="icon">💼</span> Wallet Management</h2>
            <p class="subtitle">Manage users, create wallets, and view balances easily</p>
        </div>
        <a href="{{ route('wallets.deposit.form') }}" class="btn btn-success btn-deposit">
            💰 Deposit Funds
        </a>
    </div>

    <div class="wallet-grid">

        {{-- Create Wallet --}}
        <div class="card wallet-card">
            <div class="card-header bg-primary">➕ Create Wallet for User</div>
            <div class="card-body">
                <form action="{{ route('wallets.store') }}" method="POST" class="form">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" required>
                            <option value="">-- Choose User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary full-width mt-2">
                        Create Wallet
                    </button>
                </form>
            </div>
        </div>

        <div class="card wallet-card">
            <div class="card-header bg-primary">📋 Wallet List</div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th class="text-right">Balance (Fbu)</th>
                                <th class="text-center">Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wallets as $wallet)
                                <tr>
                                    <td>{{ $wallet->user->name }}</td>
                                    <td>{{ $wallet->user->email }}</td>
                                    <td class="text-right" style="color: #28a745;">
                                        {{ number_format($wallet->solde, 2) }}
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ $wallet->updated_at->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No wallets available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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

    .wallet-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
    }

    .wallet-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
    }

    .card-header {
        padding: 12px 16px;
        font-weight: 600;
        color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .bg-primary { background-color: #0056b3; }
    .bg-dark { background-color: #333; }

    .card-body {
        padding: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
    }

    select, input {
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    select:focus, input:focus {
        border-color: #0056b3;
        outline: none;
        box-shadow: 0 0 3px rgba(0, 116, 217, 0.3);
    }

    .btn {
        padding: 8px 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-primary { background-color: #0056b3; color: #fff; }
    .btn-success { background-color: #28a745; color: #fff; }
    .btn:hover { opacity: 0.9; }
    .btn-deposit {
        box-shadow: 0 3px 6px rgba(46, 204, 113, 0.4);
    }

    .full-width { width: 100%; }
    .mt-2 { margin-top: 10px; }

    .table-wrapper {
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .table th, .table td {
        padding: 10px 8px;
        border-bottom: 1px solid #eee;
    }

    .table th {
        background-color: #f9f9f9;
        text-align: left;
        font-weight: 600;
    }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-muted { color: #999; }
    .text-green { color: #2ecc71; }

    @media (max-width: 992px) {
        .wallet-grid {
            grid-template-columns: 1fr;
        }
        .wallet-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>
@endpush
