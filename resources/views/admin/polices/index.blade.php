@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h3">
                        <i class="fas fa-file-contract text-primary"></i>
                        Gestion des Polices d'Assurance
                    </h1>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card filter-card mb-4">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-filter"></i> Filtres de recherche
                </h5>
                <form method="GET" action="{{ route('admin.polices.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Client</label>
                            <select name="user_id" class="form-select">
                                <option value="">Tous les clients</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                @foreach($statuts as $key => $label)
                                    <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Numéro Police</label>
                            <input type="text" name="numero_police" class="form-control" 
                                   value="{{ request('numero_police') }}" placeholder="Rechercher...">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Liste des Polices
                        <span class="badge bg-secondary">{{ $polices->total() }}</span>
                    </h5>
                </div>
            </div>

            <div class="card-body p-0">
                @if($polices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Numéro Police</th>
                                    <th>Client</th>
                                    <th>Véhicule</th>
                                    <th>Période</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($polices as $police)
                                    <tr>
                                        <td>
                                            <strong>{{ $police->numero_police }}</strong>
                                        </td>
                                        <td>
                                            <strong>{{ $police->vehicule->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $police->vehicule->user->nationality ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $police->vehicule->marque }} - {{ $police->vehicule->modele }}
                                            <br>
                                            <small class="text-muted">{{ $police->vehicule->immatriculation }}</small>
                                        </td>
                                        <td>
                                            {{ optional($police->date_debut)->format('d/m/Y') ?? '-' }}
                                            <br>
                                            <small class="text-muted">au {{ optional($police->date_fin)->format('d/m/Y') ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = [
                                                    'En attente' => 'badge-en-attente',
                                                    'Active' => 'badge-active',
                                                    'Expiré' => 'badge-expiree'
                                                ][$police->statut] ?? 'badge-secondary';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $statuts[$police->statut] ?? $police->statut }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                
                                                @if($police->statut == 'En attente')
                                                    <form action="{{ route('admin.polices.approve', $police->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success" 
                                                                title="Valider la police">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- @if(in_array($police->statut, ['active', 'expiree']))
                                                    <form action="{{ route('polices.renouveler', $police->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning" 
                                                                title="Renouveler la police">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </form>
                                                @endif -->

                                                <!-- @if($police->statut == 'active')
                                                    <a href="{{ route('polices.pdf', $police->id) }}" 
                                                       class="btn btn-outline-danger" title="Télécharger PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @endif -->
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucune police trouvée</h4>
                        <p class="text-muted">Aucune police ne correspond à vos critères de recherche.</p>
                        <a href="{{ route('admin.polices.index') }}" class="btn btn-primary">
                            <i class="fas fa-refresh"></i> Réinitialiser la recherche
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<script>
    // Confirmation pour les actions critiques
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.querySelector('button[type="submit"]').title === 'Valider la police' || 
                    this.querySelector('button[type="submit"]').title === 'Renouveler la police') {
                    if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    });
</script>

@push('styles')
<style>
    .badge-en-attente { background-color: #ffc107; color: #000; }
    .badge-active { background-color: #198754; color: #fff; }
    .badge-expiree { background-color: #dc3545; color: #fff; }
    .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.075); }
    .filter-card { background-color: #f8f9fa; border-left: 4px solid #0d6efd; }
</style>
@endpush