@extends('layouts.app')

@section('title', 'Gestion des Produits')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <!-- Fil d'Ariane (horizontal, centré) -->
            <nav aria-label="breadcrumb" class="mb-4 horizontal-breadcrumb">
                <ol class="breadcrumb bg-white px-3 py-2 rounded-3 shadow-sm justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produits</li>
                </ol>
            </nav>

            <!-- En-tête de page (centré) -->
            <div class="d-flex flex-column align-items-center text-center mb-4 page-title-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800 d-flex align-items-center justify-content-center">
                        <i class="fas fa-cubes text-primary me-2"></i> Gestion des Produits
                    </h1>
                    <p class="text-muted mb-0">Liste complète des produits d'assurance</p>
                </div>
                <a href="{{ route('produits.create') }}" class="btn btn-primary mt-3 page-action-btn">
                    <i class="fas fa-plus-circle me-2"></i> Nouveau Produit
                </a>
            </div>

            <!-- Carte principale -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- En-tête de la carte -->
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center flex-column flex-md-row justify-content-center card-header-center">
                        <h5 class="mb-0">Liste des produits</h5>
                        <div class="d-flex mt-3 mt-md-0">
                            <form action="{{ route('produits.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Rechercher un produit..." value="{{ request('search') }}">
                                    <select name="pays" class="form-select" style="max-width: 200px;">
                                        <option value="">Tous les pays</option>
                                        @foreach($pays as $p)
                                            <option value="{{ $p->id }}" {{ request('pays') == $p->id ? 'selected' : '' }}>
                                                {{ $p->nom }} ({{ $p->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="actif" class="form-select" style="max-width: 150px;">
                                        <option value="">Tous</option>
                                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                                    </select>
                                    <button type="submit" class="btn btn-outline-primary" title="Appliquer les filtres">
                                        <i class="fas fa-filter me-1"></i> Filtrer
                                    </button>
                                    <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    @if($produits->isEmpty())
                        <div class="empty-state p-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-inbox fa-5x text-muted opacity-20"></i>
                            </div>
                            <h3 class="text-muted mb-2">Aucun produit trouvé</h3>
                            <p class="text-muted mb-4 lead">Commencez par ajouter un nouveau produit à votre catalogue</p>
                            <a href="{{ route('produits.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i> Ajouter un produit
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">#</th>
                                        <th>Produit</th>
                                        <th>Pays</th>
                                        <th>Durée</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-end pe-4" style="width: 200px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produits as $produit)
                                        <tr class="position-relative">
                                            <td class="ps-4 text-muted fw-medium">{{ $produit->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-primary bg-opacity-10 p-2 rounded">
                                                            <i class="fas fa-cube text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $produit->nom }}</h6>
                                                        @if($produit->description)
                                                            <small class="text-muted">{{ Str::limit($produit->description, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-flag me-1 text-primary"></i> 
                                                    {{ $produit->pays->nom ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $produit->duree ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($produit->actif)
                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                        <i class="fas fa-check-circle me-1"></i> Actif
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Inactif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('produits.show', $produit) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top"
                                                       title="Voir les détails">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('produits.edit', $produit) }}" 
                                                       class="btn btn-sm btn-outline-secondary" 
                                                       data-bs-toggle="tooltip" 
                                                       data-bs-placement="top"
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $produit->id }}"
                                                            data-bs-placement="top"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal de suppression
                                        <div class="modal fade" id="deleteModal{{ $produit->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-exclamation-triangle me-2"></i> Confirmation
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div> -->
                                                    <!-- <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer le produit <strong>"{{ $produit->nom }}"</strong> ?</p>
                                                        <p class="text-muted mb-0">Cette action est irréversible.</p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash me-1"></i> Supprimer
                                                            </button> -->
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($produits->hasPages())
                            <div class="d-flex justify-content-between align-items-center p-4 border-top">
                                <div class="text-muted">
                                    Affichage de <strong>{{ $produits->firstItem() }}</strong> à 
                                    <strong>{{ $produits->lastItem() }}</strong> sur 
                                    <strong>{{ $produits->total() }}</strong> produits
                                </div>
                                <div>
                                    {{ $produits->withQueryString()->links() }}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
:root {
    /* Ajusté : couleur primaire légèrement plus sombre pour meilleur contraste */
    --primary-color: #2c5aa0;
    --primary-hover: #3a56d4;
    --secondary-color: #6c757d;
    --light-bg: #f8f9fa;
    --border-color: #e9ecef;
    --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    --shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
    --radius: 0.5rem;
    --transition: all 0.2s ease-in-out;
}

/* Animations */
.animate__animated {
    animation-duration: 0.5s;
}

.animate__delay-1s {
    animation-delay: 0.3s;
}

/* Suppression du soulignement des liens */
a, a:hover {
    text-decoration: none !important;
}

/* Animation des boutons */
.btn {
    transition: transform 0.2s ease, box-shadow 0.2s ease !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

/* Animation des cartes */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease !important;
}

.card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Animation des lignes du tableau */
.table tbody tr {
    transition: all 0.2s ease !important;
}

.table tbody tr:hover {
    background-color: #f8f9fa !important;
    transform: translateX(5px);
}
.card {
    border: none;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    margin-bottom: 1.5rem;
}

.card:hover {
    box-shadow: var(--shadow);
}

.card-header {
    background-color: white;
    border-bottom: 1px solid var(--border-color);
}

.table {
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6b7280;
    padding: 1rem 1.5rem;
    background-color: #f9fafb;
    border-bottom: 1px solid var(--border-color);
}

.table tbody td {
    padding: 1rem 1.5rem;
    vertical-align: middle;
    border-color: var(--border-color);
}

.table tbody tr {
    transition: background-color 0.15s ease;
}

.table tbody tr:hover {
    background-color: #f8fafc;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-sm {
    padding: 0.35rem 0.65rem;
    font-size: 0.8rem;
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--border-color);
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-outline-secondary {
    color: var(--secondary-color);
    border-color: var(--border-color);
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    color: #374151;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: var(--border-color);
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
}

/* Centering tweaks for this page */
.page-title-center h1,
.page-title-center p {
    text-align: center;
}

.page-action-btn {
    box-shadow: 0 6px 18px rgba(67,97,238,0.12);
}
.horizontal-breadcrumb{
    margin-bottom: 20px;
    gap: 60px;
}

/* Make breadcrumb vertical and visually distinct for this page */
.vertical-breadcrumb .breadcrumb {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
    padding: 0.75rem 1rem;
}

.vertical-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
    display: none; /* remove the '/' separator when vertical */
}

.vertical-breadcrumb .breadcrumb-item a {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Center card header controls */
.card-header-center {
    gap: 1rem;
}

.card-header-center .input-group {
    justify-content: center;
}

/* Slightly center pagination and table footer */
.table-footer,
.d-flex.justify-content-between.align-items-center.p-4.border-top {
    justify-content: center !important;
    gap: 1rem;
    text-align: center;
}

/* Ensure table actions stay usable while the rest is centered */
table td.text-end.pe-4 {
    text-align: center !important;
}

/* Sidebar polish: make icons and text slightly larger and centered vertically */
.sidebar-nav .nav-item {
    padding: 14px 18px;
}

/* Responsive: keep centered layout on larger screens but allow table to scroll on small screens */
@media (min-width: 1200px) {
    .page-title-center {
        max-width: 1100px;
        margin: 0 auto;
    }
    .card {
        max-width: 1100px;
        margin: 0 auto;
    }
}

/* Horizontal breadcrumb and empty state styles */
.horizontal-breadcrumb .breadcrumb {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 0.75rem;
    justify-content: center;
}

.horizontal-breadcrumb .breadcrumb-item a {
    color: #374151;
    font-weight: 600;
}

.horizontal-breadcrumb .breadcrumb-item.active {
    color: var(--primary-color, #4361ee);
    font-weight: 700;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3.5rem 2rem;
    background: linear-gradient(180deg, rgba(67,97,238,0.03), rgba(67,97,238,0.01));
    border: 1px dashed rgba(67,97,238,0.08);
    border-radius: 0.75rem;
    margin: 2rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #374151;
}

.empty-state .lead {
    font-size: 1rem;
    color: #6b7280;
}

/* Slight typography adjustments for headers and table */
h1.h3 {
    font-size: 1.6rem;
    letter-spacing: -0.2px;
}

table td, table th {
    padding: 0.85rem 1rem;
}

@media (max-width: 991.98px) {
    .horizontal-breadcrumb .breadcrumb {
        flex-direction: column;
    }
}

/* Precise adjustments: tailles, espacements et couleurs */
:root {
    /* valeurs précises d'exemple demandées */
    --produits-h1-size: 24px; /* Titre h1 = 24px */
    --produits-btn-font: 16px; /* Bouton = 16px */
    --produits-empty-h3: 20px; /* Empty state title */
    --produits-table-padding-vertical: 12px; /* padding vertical des cellules */
}

/* Titre principal */
.page-title-center h1.h3 {
    font-size: var(--produits-h1-size) !important;
    font-weight: 700;
    color: #1f2937; /* gris foncé */
}

/* Boutons */
.btn, .btn-primary, .btn-outline-primary {
    font-size: var(--produits-btn-font) !important;
    padding: 0.55rem 1rem !important;
}
.btn-lg {
    padding: 0.75rem 1.25rem !important;
    font-size: var(--produits-btn-font) !important;
}

.sidebar-nav {
    /* Forcer un layout flex colonne pour que gap fonctionne correctement */
    display: flex;
    flex-direction: column;
    gap: 12px !important; /* espacement entre éléments (valeur raisonnable) */
    padding: 8px 18px;
}
.sidebar-nav .nav-item {
    padding: 12px 18px;
    border-radius: 8px;
    transition: background-color 0.18s ease, transform 0.12s ease;
}
/* Fallback pour navigateurs ne supportant pas gap sur flex */
.sidebar-nav > .nav-item:not(:last-child) {
    margin-bottom: 12px;
}
.sidebar-nav .nav-item i {
    font-size: 1.05rem;
}
.sidebar-nav .nav-item span {
    font-size: 0.95rem;
}

/* Empty state sizes */
.empty-state h3 {
    font-size: var(--produits-empty-h3) !important;
    margin-bottom: 8px;
}
.empty-state .lead {
    font-size: 1rem;
}

/* Table spacing */
table th, table td {
    padding: var(--produits-table-padding-vertical) 1rem !important;
}

/* Contrastes et couleurs précises */
.btn-primary {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}
.btn-primary:hover {
    background-color: var(--primary-hover) !important;
    border-color: var(--primary-hover) !important;
}

/* Petite amélioration responsive pour sidebar */
@media (max-width: 1024px) {
    .sidebar-nav {
        gap: 50px;
        padding-left: 12px;
        padding-right: 12px;
    }
}

/* Style pour la pagination */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border: none;
    color: #6b7280;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem !important;
    margin: 0 0.15rem;
    font-size: 0.875rem;
    min-width: 2.25rem;
    text-align: center;
    transition: all 0.2s;
}

.page-item.active .page-link {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.page-link:hover {
    background-color: #f3f4f6;
    color: var(--primary-color);
}

/* Style pour les formulaires */
.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
}

/* Style pour les alertes */
.alert {
    border: none;
    border-radius: 0.375rem;
    padding: 1rem 1.25rem;
}

.alert-success {
    background-color: #ecfdf5;
    color: #047857;
    border-left: 4px solid #10b981;
}

/* Style pour les modales */
.modal-content {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
    padding: 1.25rem 1.5rem;
}

.modal-footer {
    border-top: 1px solid var(--border-color);
    padding: 1rem 1.5rem;
}

/* Style pour les tooltips */
.tooltip {
    font-size: 0.75rem;
}

/* Style pour les écrans mobiles */
@media (max-width: 991.98px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .table-responsive {
        border: none;
    }
    
    .table thead {
        display: none;
    }
    
    .table tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .table tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        margin-right: 1rem;
        color: #6b7280;
        width: 120px;
        flex-shrink: 0;
    }
    
    .table tbody td:last-child {
        border-bottom: none;
    }
    
    .btn-group {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@push('scripts')
<!-- Animate.css pour les animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Désactiver la soumission du formulaire si la recherche est vide
    const searchForm = document.querySelector('form[action="{{ route("produits.index") }}"]');
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="search"]');
        searchForm.addEventListener('submit', function(e) {
            if (searchInput && searchInput.value.trim() === '' && 
                !searchForm.querySelector('select[name="pays"]').value && 
                !searchForm.querySelector('select[name="actif"]').value) {
                e.preventDefault();
                window.location.href = "{{ route('produits.index') }}";
            }
        });
    }

    // Ajouter des animations au chargement
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.classList.add('animate__animated', 'animate__fadeInUp');
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endpush