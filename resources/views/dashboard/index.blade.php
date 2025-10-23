@extends('layouts.app')

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

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon clients">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statClients">{{ $stats['clients'] ?? 0 }}</div>
            <div class="stat-label">Clients</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            12%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon polices">
            <i class="fas fa-file-contract"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statPolices">{{ $stats['polices'] ?? 0 }}</div>
            <div class="stat-label">Polices</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            8%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon sinistres">
            <i class="fas fa-car-crash"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statSinistres">{{ $stats['sinistres'] ?? 0 }}</div>
            <div class="stat-label">Sinistres</div>
        </div>
        <div class="stat-trend down">
            <i class="fas fa-arrow-down"></i>
            5%
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon revenue">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-info">
            <div class="stat-value" id="statRevenue">{{ number_format($stats['revenue'] ?? 0, 2, ',', ' ') }} BIF</div>
            <div class="stat-label">Chiffre d'affaires</div>
        </div>
        <div class="stat-trend up">
            <i class="fas fa-arrow-up"></i>
            15%
        </div>
    </div>
</div>

<div class="charts-grid">
    <div class="chart-card">
        <h3>Polices par pays</h3>
        <div id="chartPays" class="chart-container"></div>
    </div>
    <div class="chart-card">
        <h3>Sinistres par mois</h3>
        <div id="chartSinistres" class="chart-container"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les graphiques
    if (typeof ApexCharts !== 'undefined') {
        // Graphique des polices par pays
        const chartPays = new ApexCharts(document.querySelector("#chartPays"), {
            chart: { type: 'donut' },
            series: [44, 55, 13, 43, 22],
            labels: ['Burundi', 'Rwanda', 'Kenya', 'Tanzanie', 'RDC']
        });
        chartPays.render();

        // Graphique des sinistres par mois
        const chartSinistres = new ApexCharts(document.querySelector("#chartSinistres"), {
            chart: { type: 'bar' },
            series: [{
                name: 'Sinistres',
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125, 100, 85, 95]
            }],
            xaxis: {
                categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc']
            }
        });
        chartSinistres.render();
    }

    // Rafraîchir les statistiques
    document.getElementById('refreshStats').addEventListener('click', function() {
        // Implémentez la logique de rafraîchissement ici
        console.log('Rafraîchissement des statistiques...');
    });
});
</script>
@endpush