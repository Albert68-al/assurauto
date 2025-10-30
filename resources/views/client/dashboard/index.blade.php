@extends('client.layouts.clientapp')

@section('title', 'Tableau de bord - AssurAuto')

@section('content')
<div class="section-header">
    <h2>Tableau de bord</h2>
</div>

<div class="stats-grid-client">
    <div class="stat-card-client">
        <div class="stat-icon clients">
            <i class="fas fa-car"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statClients">{{ $stats['vehicules'] ?? 0 }}</div>
            <div class="stat-label">Mes Véhicules</div>
        </div>
    </div>

    <div class="stat-card-client">
        <div class="stat-icon polices">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statPolices">{{ $stats['polices'] ?? 0 }}</div>
            <div class="stat-label">Mes Assurances</div>
        </div>
    </div>

    <div class="stat-card-client">
        <div class="stat-icon sinistres">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statSinistres">{{ $stats['sinistres'] ?? 0 }}</div>
            <div class="stat-label">Mes Sinistres</div>
        </div>

    </div>

    <div class="stat-card-client">
        <div class="stat-icon revenue">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statRevenue">{{ number_format($stats['wallet'] ?? 0, 2, ',', ' ') }} Fbu</div>
            <div class="stat-label">Mon Portefeuille</div>
        </div>
    </div>
</div>

<div class="notif-card">
    <h2 class="notif-title">🛡️ Produits disponibles</h2>
    <h3 class="notif-subtitle">🛡️ Produits disponibles</h3>

    
    <div class="products-grid">
        @forelse($produits as $produit)
            <div class="product-card">
                <div class="product-header">
                    <h4 class="product-name">{{ $produit->nom }}</h4>
                    <span class="status-badge active">{{ number_format($produit->tarif_base, 2, ',', ' ') }} {{ $produit->devise }}</span>
                </div>

                <p class="product-description">{{ $produit->description }}</p>
                <p class="product-description">{{ $produit->couverture }}</p>

                <div class="product-footer">
                    <button class="details">{{ $produit->pays->nom ?? 'N/A' }}</button>
                </div>
            </div>
        @empty
            <p class="no-products">Aucun produit disponible pour le moment.</p>
        @endforelse
    </div>
</div>


@endsection
