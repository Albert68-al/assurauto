@extends('client.layouts.clientapp')

@section('content')
<div class="wallet-page">
@if ($wallet)
    <div class="wallet-header">
        <div>
            <h2><span class="icon">💳</span> Mon Portefeuille</h2>
            <p class="subtitle">Consultez votre solde et vos transactions récentes</p>
        </div>
    </div>

    <div class="wallet-summary">
        <div class="summary-card">
            <h4>Solde Actuel</h4>
            <p class="balance">$ {{ number_format($wallet->solde ?? 0, 2) }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark">📋 Historique des Transactions</div>
        <div class="card-body">
            @if ($wallet && $wallet->transactions->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Solde ($)</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallet->transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if ($transaction->type === 'debit')
                                        <span class="badge success">Dépôt</span>
                                    @else ($transaction->type === 'credit')
                                        <span class="badge success">Retrait</span>
                                    @endif
                                </td>
                                <td class="{{ $transaction->type === 'deposit' ? 'text-green' : 'text-red' }}">
                                    {{ number_format($transaction->amount, 2) }}
                                </td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-transactions">Pas de transaction faite.</p>
            @endif
        </div>
    </div>
@else
<div class="no-wallet text-center">
    <h3>😕 Vous n’avez pas encore de portefeuille.</h3>
    <p>Rendez-vous auprès de l’administration pour créer votre portefeuille.</p>
</div>
@endif
</div>
@endsection

@push('styles')
<style>
.wallet-page {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 16px;
    font-family: "Segoe UI", sans-serif;
    color: #333;
}
.wallet-header {
    margin-bottom: 30px;
}
.wallet-header h2 {
    font-size: 1.8rem;
    color: #0074D9;
    margin-bottom: 4px;
}
.subtitle {
    color: #777;
}
.wallet-summary {
    margin-bottom: 30px;
}
.summary-card {
    background: linear-gradient(135deg, #0074D9, #005fa3);
    color: #fff;
    border-radius: 10px;
    padding: 24px;
    text-align: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.summary-card h4 {
    margin: 0 0 8px;
    font-weight: 500;
}
.balance {
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    overflow: hidden;
}
.card-header {
    padding: 12px 16px;
    font-weight: 600;
    color: #fff;
}
.bg-dark { background-color: #333; }
.card-body { padding: 16px; }
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th, .table td {
    padding: 10px 8px;
    border-bottom: 1px solid #eee;
    font-size: 0.95rem;
}
.text-green { color: #2ecc71; font-weight: 600; }
.text-red { color: #e74c3c; font-weight: 600; }
.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
}
.badge.success { background-color: #2ecc71; }
.badge.danger { background-color: #e74c3c; }
.badge.secondary { background-color: #999; }
.no-transactions {
    text-align: center;
    color: #777;
    padding: 20px 0;
}
</style>
@endpush
