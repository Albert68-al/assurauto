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

            <div class="card products-show-card shadow-sm">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0">
                        <i class="fas fa-cube me-2"></i>{{ $produit->nom }}
                    </h3>
                </div>

                <div class="card-body text-center">
                    <div class="mb-3">
                        <a href="{{ route('produits.edit', $produit) }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour
                        </a>
                    </div>

                    <div class="row justify-content-center align-items-center mb-4">
                        <div class="col-12 col-md-7">
                            <div class="d-flex flex-column align-items-center">
                                <div class="avatar bg-white rounded-circle p-3 shadow-sm mb-3">
                                    <i class="fas fa-cube fa-2x text-primary"></i>
                                </div>
                                <h2 class="mb-1">{{ $produit->nom }}</h2>
                                <div class="mb-2">
                                    <span class="badge {{ $produit->actif ? 'bg-success' : 'bg-secondary' }} me-2">
                                        {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                    </span>
                                    <small class="text-muted"><i class="far fa-clock me-1"></i>Mis à jour le {{ $produit->updated_at->format('d/m/Y à H:i') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 mt-3 mt-md-0 text-center">
                            @php
                                $montantAvecTaux = $produit->tarif_base * (1 + ($produit->taux / 100));
                                $montantTTC = $montantAvecTaux * (1 + ($produit->tva / 100));
                            @endphp
                            <div class="h3 mb-0 text-primary fw-bold">{{ number_format($produit->tarif_base, 2, ',', ' ') }} {{ $produit->devise }}</div>
                            <div class="text-muted small">Tarif de base HT</div>
                            <div class="mt-3">
                                <div class="d-inline-block px-3 py-1 rounded-3 bg-white shadow-sm">
                                    <small class="text-muted me-2">Taux:</small>
                                    <strong class="me-3">{{ $produit->taux > 0 ? $produit->taux . '%' : 'Aucun' }}</strong>
                                    <small class="text-muted me-2">TVA:</small>
                                    <strong>{{ $produit->tva > 0 ? $produit->tva . '%' : 'Aucune' }}</strong>
                                </div>
                            </div>
                            <div class="h5 mt-2 fw-semibold">TTC: <span class="text-primary">{{ number_format($montantTTC, 2, ',', ' ') }}</span> {{ $produit->devise }}</div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Description et couverture</h5>
                                </div>
                                <div class="card-body text-start">
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
                                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Historique des modifications</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">Création du produit</h6>
                                                    <small class="text-muted">{{ $produit->created_at->format('d/m/Y à H:i') }}</small>
                                                </div>
                                                <p class="mb-0 small text-muted">Par {{ $produit->user?->name ?? 'Utilisateur inconnu' }}</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1">Dernière mise à jour</h6>
                                                    <small class="text-muted">{{ $produit->updated_at->format('d/m/Y à H:i') }}</small>
                                                </div>
                                                <p class="mb-0 small text-muted">Modifications apportées</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Toast container -->
            <div aria-live="polite" aria-atomic="true" class="position-relative">
                <div class="toast-container position-fixed bottom-0 end-0 p-3">
                    <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto">Notification</strong>
                            <small class="text-muted"></small>
                            <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body"></div>
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
