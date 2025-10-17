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
                    <strong>Nom :</strong> {{ $produit->name }}<br>
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
    .timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        padding-left: 1.5rem;
        border-left: 1px solid #e9ecef;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
        border-left: 1px solid transparent;
    }
    .timeline-marker {
        position: absolute;
        left: -0.5rem;
        width: 1rem;
        height: 1rem;
        border-radius: 100%;
        border: 2px solid #fff;
        margin-top: 0.25rem;
    }
    .timeline-content {
        padding-left: 0.5rem;
    }
    .bg-opacity-10 {
        opacity: 0.1;
    }
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
