@extends('layouts.app')

@section('title', 'Gestion des Produits')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0">
                    <i class="fas fa-cubes"></i> Gestion des Produits
                </h1>
                <div>
                    <a href="{{ route('produits.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nouveau Produit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <form action="{{ route('produits.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="pays" class="form-label">Filtrer par pays</label>
                    <select name="pays" id="pays" class="form-select">
                        <option value="">Tous les pays</option>
                        @foreach($pays as $p)
                            <option value="{{ $p->id }}" {{ request('pays') == $p->id ? 'selected' : '' }}>
                                {{ $p->nom }} ({{ $p->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="actif" class="form-label">Statut</label>
                    <select name="actif" id="actif" class="form-select">
                        <option value="">Tous</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('produits.index') }}" class="btn btn-light">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($produits->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Aucun produit trouvé.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Pays</th>
                                <th class="text-end">Tarif de base</th>
                                <th class="text-center">Taux/TVA</th>
                                <th class="text-center">Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produits as $produit)
                                <tr>
                                    <td>{{ $produit->id }}</td>
                                    <td>
                                        <strong>{{ $produit->nom }}</strong>
                                        @if($produit->description)
                                            <p class="text-muted mb-0 small">{{ Str::limit($produit->description, 50) }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        @if($produit->pays)
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-flag me-1"></i> {{ $produit->pays->code }}
                                            </span>
                                        @else
                                            <span class="text-muted">Non défini</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($produit->tarif_base, 2, ',', ' ') }} {{ $produit->devise }}
                                    </td>
                                    <td class="text-center">
                                        @if($produit->taux > 0 || $produit->tva > 0)
                                            <span class="badge bg-info text-dark">
                                                Taux: {{ $produit->taux }}%<br>
                                                TVA: {{ $produit->tva }}%
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">Aucun</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-flex justify-content-center">
                                            <input type="checkbox" class="form-check-input toggle-status" 
                                                data-id="{{ $produit->id }}" 
                                                id="toggle-{{ $produit->id }}"
                                                {{ $produit->actif ? 'checked' : '' }}>
                                            <label class="form-check-label ms-2" for="toggle-{{ $produit->id }}">
                                                <span class="badge {{ $produit->actif ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $produit->actif ? 'Actif' : 'Inactif' }}
                                                </span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('produits.show', $produit) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               data-bs-toggle="tooltip" 
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('produits.edit', $produit) }}" 
                                               class="btn btn-sm btn-outline-warning"
                                               data-bs-toggle="tooltip" 
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('produits.destroy', $produit) }}" 
                                                  method="POST" 
                                                  class="d-inline" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        data-bs-toggle="tooltip" 
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="text-end">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Affichage de {{ $produits->firstItem() }} à {{ $produits->lastItem() }} sur {{ $produits->total() }} produits
                                        </div>
                                        {{ $produits->withQueryString()->links() }}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Gérer le basculement du statut
        document.querySelectorAll('.toggle-status').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
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
        });
    });
</script>
@endpush

<!-- Toast pour les notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-check-circle text-success me-2"></i>
            <strong class="me-auto">Succès</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Le statut a été mis à jour avec succès.
        </div>
    </div>
</div>

<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-weight: 500;
    }
    .form-switch .form-check-input {
        width: 2.5em;
        margin-left: -2.7em;
        cursor: pointer;
    }
    .form-switch .form-check-label {
        cursor: pointer;
    }
    .toast {
        opacity: 1 !important;
    }
</style>
@endsection
