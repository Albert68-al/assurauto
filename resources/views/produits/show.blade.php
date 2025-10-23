@extends('layouts.app')

@section('title', 'Détails du Produit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $produit->nom }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-cube me-2"></i>{{ $produit->nom }}
                    </h3>
                    <div>
                        <a href="{{ route('produits.edit', $produit) }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('produits.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                        <i class="fas fa-cube fa-2x text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-1">{{ $produit->nom }}</h2>
                                    <div class="d-flex align-items-center">
                                        <span class="badge {{ $produit->actif ? 'bg-success' : 'bg-secondary' }} me-2">
                                            {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                        </span>
                                        <span class="text-muted small">
                                            <i class="far fa-clock me-1"></i>
                                            Mis à jour le {{ $produit->updated_at->format('d/m/Y à H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="h3 mb-0 text-primary">
                                {{ number_format($produit->tarif_base, 2, ',', ' ') }} {{ $produit->devise }}
                            </div>
                            <div class="text-muted">Tarif de base HT</div>
                            
                            @php
                                $montantAvecTaux = $produit->tarif_base * (1 + ($produit->taux / 100));
                                $montantTTC = $montantAvecTaux * (1 + ($produit->tva / 100));
                            @endphp
                            
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    Taux: {{ $produit->taux > 0 ? $produit->taux . '%' : 'Aucun' }} | 
                                    TVA: {{ $produit->tva > 0 ? $produit->tva . '%' : 'Aucune' }}
                                </span>
                            </div>
                            <div class="h5 mt-2">
                                TTC: {{ number_format($montantTTC, 2, ',', ' ') }} {{ $produit->devise }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Description et couverture
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($produit->description)
                                        <h6>Description</h6>
                                        <div class="p-3 bg-light rounded mb-4">
                                            {!! nl2br(e($produit->description)) !!}
                                        </div>
                                    @endif

                                    <h6>Couverture</h6>
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($produit->couverture)) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Historique des modifications
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">Création du produit</h6>
                                                    <small class="text-muted">
                                                        {{ $produit->created_at->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>
                                                <p class="mb-0 small text-muted">
                                                    Par {{ $produit->user?->name ?? 'Utilisateur inconnu' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">Dernière mise à jour</h6>
                                                    <small class="text-muted">
                                                        {{ $produit->updated_at->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>
                                                <p class="mb-0 small text-muted">
                                                    Modifications apportées
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cog me-2"></i>Configuration
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6>Pays</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-flag me-2 text-muted"></i>
                                            <span>{{ $produit->pays->nom ?? 'Non spécifié' }} ({{ $produit->pays->code ?? 'N/A' }})</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Devise</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-money-bill-wave me-2 text-muted"></i>
                                            <span>{{ $produit->devise }} ({{ $produit->pays->devise ?? 'N/A' }})</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Statut</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-status" type="checkbox" 
                                                           data-id="{{ $produit->id }}" 
                                                           id="toggle-{{ $produit->id }}"
                                                           {{ $produit->actif ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="toggle-{{ $produit->id }}">
                                                        <span class="badge {{ $produit->actif ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Date de création</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                                            <span>{{ $produit->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h6>Dernière mise à jour</h6>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-sync-alt me-2 text-muted"></i>
                                            <span>{{ $produit->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-shield-alt me-2"></i>Politiques
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash-alt me-1"></i> Supprimer ce produit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement ce produit ?</p>
                <p class="mb-0">
                    <strong>Nom :</strong> {{ $produit->nom }}<br>
                    <strong>Référence :</strong> #{{ str_pad($produit->id, 6, '0', STR_PAD_LEFT) }}
                </p>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Cette action est irréversible. Toutes les données associées à ce produit seront également supprimées.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root{
    --page-bg: #f8f9fa;
    --text-color: #0b0f14;
    --muted: #6c757d;
    --accent: #007bff;
    --card-bg: #ffffffd9;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-strong: 0 6px 18px rgba(0,0,0,0.08);
    --radius: 12px;
    --timeline-line: #dee2e6;
    --success-soft: #e6fbf0;
    --success-text: #1f8a44;
    --inactive-soft: #f1f3f5;
}

/* Page base */
body {
    background-color: var(--page-bg) !important;
    color: var(--text-color);
    -webkit-font-smoothing:antialiased;
    -moz-osx-font-smoothing:grayscale;
    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
}

/* Center and constrain main card */
.products-show-card {
    max-width: 1000px;
    margin: 2rem auto;
    border: none;
    background: transparent;
    padding: 0 1rem;
}

/* Header area (product name) */
.products-show-card .card-header {
    background: linear-gradient(180deg,var(--accent), #0069d9);
    color: #fff;
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    font-weight: 600;
    box-shadow: var(--shadow-sm);
    text-align: center;
}

/* Main body card */
.products-show-card .card-body {
    background: var(--card-bg);
    padding: 1.5rem;
    border-radius: calc(var(--radius) - 2px);
    box-shadow: var(--shadow-sm);
    margin-top: 0.75rem;
    animation: cardFadeUp 360ms ease both;
}

/* Headings and muted text */
.products-show-card h2,
.products-show-card h3,
.products-show-card h5 {
    color: var(--text-color);
    font-weight: 600;
}

.products-show-card .text-muted,
.products-show-card small {
    color: var(--muted);
    font-size: .95rem;
}

/* Avatar / icon */
.products-show-card .avatar {
    width: 88px;
    height: 88px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    background: #fff;
    box-shadow: 0 6px 18px rgba(11,15,20,0.06);
}

/* Price block */
.products-show-card .price-base {
    color: var(--text-color);
    font-weight: 700;
}

/* Controls/buttons */
.products-show-card .btn {
    border-radius: 999px;
    transition: transform .14s ease, box-shadow .14s ease, background-color .14s ease;
    font-weight: 600;
}

.products-show-card .btn-light {
    background: #fff;
    color: var(--text-color);
    border: 1px solid rgba(11,15,20,0.06);
    box-shadow: 0 2px 6px rgba(11,15,20,0.04);
}

.products-show-card .btn-light:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-strong);
}

/* Outline button */
.products-show-card .btn-outline-secondary {
    color: var(--text-color);
    background: transparent;
    border-radius: 999px;
}

/* Badges */
.products-show-card .badge {
    border-radius: 999px;
    padding: .45rem .7rem;
    font-weight: 700;
}

.products-show-card .badge.bg-success {
    background: var(--success-soft);
    color: var(--success-text);
    box-shadow: none;
    border: 1px solid rgba(31,138,68,0.06);
}

.products-show-card .badge.bg-secondary {
    background: var(--inactive-soft);
    color: #6b6f74;
    border: 1px solid rgba(11,15,20,0.03);
}

/* Section cards inside page */
.products-show-card .card + .card {
    margin-top: 1rem;
}

/* Card headers inside sections */
.products-show-card .card .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(11,15,20,0.04);
    padding: .8rem 1rem;
    font-weight: 600;
    color: var(--text-color);
}

/* Card bodies (sections) */
.products-show-card .card .card-body {
    background: #fff;
    padding: 1.25rem;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(11,15,20,0.04);
    text-align: justify;
    color: var(--text-color);
}

/* Small separator under section titles */
.products-show-card .section-sep {
    height: 1px;
    background: rgba(11,15,20,0.06);
    margin: .6rem 0 1rem;
}

/* Timeline modernization */
.timeline {
    position: relative;
    padding-left: 1.25rem;
    margin: 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--timeline-line);
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.25rem;
    padding-left: 1.25rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 2px 6px rgba(11,15,20,0.08);
    transform: translateX(-2px);
}

.timeline-marker.blue { background: var(--accent); }
.timeline-marker.green { background: #28a745; }

.timeline-content {
    margin-left: 28px;
    background: #fff;
    padding: .8rem 1rem;
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(11,15,20,0.04);
}

/* Toast container */
.toast-container { z-index: 1200; }

/* Animations */
@keyframes cardFadeUp {
    from { opacity: 0; transform: translateY(6px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Utility tweaks */
.products-show-card .small { color: var(--muted); }
.products-show-card .h3, .products-show-card .h5 { color: var(--text-color); }

/* Responsive adjustments */
@media (max-width: 991.98px) {
    .products-show-card { margin: 1rem; }
    .products-show-card .card-body { padding: 1rem; }
    .products-show-card .avatar { width: 72px; height: 72px; }
    .timeline::before { left: 6px; }
    .timeline-content { margin-left: 26px; padding: .7rem; }
    .products-show-card h2 { text-align: center; }
    .products-show-card .card .card-body { text-align: center; }
    .products-show-card .timeline-content { text-align: left; }
}

/* Hover focus subtle transitions for links in content */
.products-show-card a {
    color: var(--accent);
    text-decoration: none;
    transition: color .14s ease;
}
.products-show-card a:hover {
    text-decoration: underline;
}

/* Ensure horizontal alignment of header badges & timestamp */
.products-show-card .d-flex.align-items-center { gap: .5rem; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer le basculement du statut
        const toggleStatus = document.querySelector('.toggle-status');
        if (toggleStatus) {
            toggleStatus.addEventListener('change', function() {
                const produitId = this.dataset.id;
                const isActive = this.checked;
                
                // Désactiver le toggle pendant la requête
                this.disabled = true;
                
                fetch(`/produits/${produitId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        _method: 'PATCH'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour visuellement l'état du toggle
                        const badge = this.nextElementSibling.querySelector('.badge');
                        if (data.actif) {
                            badge.classList.remove('bg-secondary');
                            badge.classList.add('bg-success');
                            badge.textContent = 'Actif';
                        } else {
                            badge.classList.remove('bg-success');
                            badge.classList.add('bg-secondary');
                            badge.textContent = 'Inactif';
                        }
                        
                        // Afficher une notification
                        const toast = new bootstrap.Toast(document.getElementById('statusToast'));
                        const toastBody = document.querySelector('#statusToast .toast-body');
                        toastBody.textContent = data.message;
                        toast.show();
                    } else {
                        // Revenir à l'état précédent en cas d'erreur
                        this.checked = !isActive;
                        alert('Une erreur est survenue lors de la mise à jour du statut.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    this.checked = !isActive;
                    alert('Une erreur est survenue lors de la communication avec le serveur.');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        }
    });
</script>
@endpush
