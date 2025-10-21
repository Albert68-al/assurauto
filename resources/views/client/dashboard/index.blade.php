@extends('client.layouts.clientapp')

@section('title', 'Tableau de bord - AssurAuto')

@section('content')
<div class="section-header">
    <h2>Tableau de bord</h2>
    <div class="section-actions">
        <button class="btn-refresh" id="refreshStats">
            <i class="fas fa-sync-alt"></i>
            Actualiser
        </button>
    </div>
</div>

<div class="stats-grid-client">
    <div class="stat-card-client">
        <div class="stat-icon clients">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statClients">{{ $stats['clients'] ?? 0 }}</div>
            <div class="stat-label">Clients</div>
        </div>
    </div>

    <div class="stat-card-client">
        <div class="stat-icon polices">
            <i class="fas fa-file-contract"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statPolices">{{ $stats['polices'] ?? 0 }}</div>
            <div class="stat-label">Polices</div>
        </div>
    </div>

    <div class="stat-card-client">
        <div class="stat-icon sinistres">
            <i class="fas fa-car-crash"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statSinistres">{{ $stats['sinistres'] ?? 0 }}</div>
            <div class="stat-label">Sinistres</div>
        </div>

    </div>

    <div class="stat-card-client">
        <div class="stat-icon revenue">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statRevenue">{{ number_format($stats['revenue'] ?? 0, 2, ',', ' ') }} €</div>
            <div class="stat-label">Chiffre d'affaires</div>
        </div>
    </div>
</div>

<div class="notif-card">
    <h2>Notifications recentes</h2>
</div>

@endsection
