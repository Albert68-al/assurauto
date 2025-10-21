@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="container-fluid bg-light min-vh-100 py-4 products-layout-horizontal-sidebar">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Fil d'Ariane -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white px-3 py-2 rounded-3 shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}" class="text-decoration-none">Produits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouveau produit</li>
                </ol>
            </nav>

            <!-- Carte principale -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <!-- En-tête de la carte -->
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle fa-lg me-2"></i>
                        <h4 class="mb-0">Nouveau Produit</h4>
                    </div>
                </div>
                
                <!-- Corps de la carte -->
                <div class="card-body p-0">
                    <form action="{{ route('produits.store') }}" method="POST" id="produitForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-0">
                            <!-- Colonne de gauche - Informations générales -->
                            <div class="col-lg-8 p-4">
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3 d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i> Informations générales
                                    </h5>
                                    <div class="card border-0 bg-light p-3">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label fw-medium required">Nom du produit</label>
                                            <input type="text" class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom') }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label fw-medium">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                     id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            <div class="form-text">Décrivez brièvement le produit et ses caractéristiques.</div>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-0">
                                            <label for="couverture" class="form-label fw-medium required">Couverture</label>
                                            <textarea class="form-control @error('couverture') is-invalid @enderror" 
                                                     id="couverture" name="couverture" rows="4" required>{{ old('couverture') }}</textarea>
                                            <div class="form-text">Détaillez les garanties et conditions de couverture.</div>
                                            @error('couverture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- Tarification (insérée ici) -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3 d-flex align-items-center">
                                        <i class="fas fa-calculator me-2"></i> Tarification
                                    </h5>
                                    <div class="card border-0 bg-white p-3">
                                        <div class="mb-3">
                                            <label for="tarif_base" class="form-label fw-medium required">Tarif de base</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" 
                                                       class="form-control @error('tarif_base') is-invalid @enderror" 
                                                       id="tarif_base" name="tarif_base" 
                                                       value="{{ old('tarif_base') }}" required>
                                                <span class="input-group-text" id="devise-symbole">$</span>
                                                @error('tarif_base')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="taux" class="form-label fw-medium">Taux spécifique (%)</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" max="100" 
                                                       class="form-control @error('taux') is-invalid @enderror" 
                                                       id="taux" name="taux" 
                                                       value="{{ old('taux', 0) }}">
                                                <span class="input-group-text">%</span>
                                                @error('taux')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Laissez vide pour utiliser le taux par défaut.</div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="tva" class="form-label fw-medium">TVA (%)</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" max="100" 
                                                       class="form-control @error('tva') is-invalid @enderror" 
                                                       id="tva" name="tva" 
                                                       value="{{ old('tva') }}">
                                                <span class="input-group-text">%</span>
                                                @error('tva')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-text">Laissez vide pour utiliser la TVA par défaut.</div>
                                        </div>
                                        
                                        <div class="alert alert-info p-3 mb-0">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <div>
                                                    <div class="fw-medium mb-1">Tarif TTC estimé :</div>
                                                    <div id="tarif-ttc" class="h4 fw-bold text-primary">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>

                            <!-- Colonne de droite - Configuration et tarification -->
                            <div class="col-lg-4 bg-light p-4">
                                <!-- Configuration -->
                                <div class="mb-4">
                                    <h5 class="text-primary mb-3 d-flex align-items-center">
                                        <i class="fas fa-cog me-2"></i> Configuration
                                    </h5>
                                    <div class="card border-0 bg-white p-3 mb-4">
                                        <div class="mb-3">
                                            <label for="pays_id" class="form-label fw-medium required">Pays</label>
                                            <select class="form-select @error('pays_id') is-invalid @enderror" 
                                                   id="pays_id" name="pays_id" required>
                                                <option value="">Sélectionnez un pays</option>
                                                @foreach($pays as $p)
                                                    <option value="{{ $p->id }}" 
                                                        {{ old('pays_id') == $p->id ? 'selected' : '' }}>
                                                        {{ $p->nom }} ({{ $p->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pays_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="devise" class="form-label fw-medium required">Devise</label>
                                            <select class="form-select @error('devise') is-invalid @enderror" 
                                                   id="devise" name="devise" required>
                                                @foreach($devises as $code => $label)
                                                    <option value="{{ $code }}" 
                                                        {{ old('devise', 'USD') == $code ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('devise')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="actif" name="actif" value="1" 
                                                   {{ old('actif', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium" for="actif">Produit actif</label>
                                        </div>
                                    </div>

                                            <!-- Tarification (moved to left column below Couverture) -->
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center p-4 border-top form-actions">
                            <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i> Enregistrer le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #4361ee;
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

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: #2d3748;
        line-height: 1.6;
        background-color: #f5f7fb;
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
        background-color: var(--primary-color);
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .form-control, .form-select {
        border: 1px solid var(--border-color);
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        transition: var(--transition);
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #4a5568;
    }

    .form-label.required:after {
        content: " *";
        color: #e53e3e;
    }

    .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    .btn-outline-secondary {
        color: var(--secondary-color);
        border-color: var(--border-color);
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #495057;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid var(--border-color);
        color: #6c757d;
    }

    .alert {
        border: none;
        border-radius: var(--radius);
    }

    .alert-info {
        background-color: #f0f7ff;
        color: #1a56db;
    }

    .breadcrumb {
        background-color: #fff;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        box-shadow: var(--shadow-sm);
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: #6c757d;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .breadcrumb-item a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #6c757d;
    }

    /* Style pour les onglets */
    .nav-tabs {
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        margin-right: 0.5rem;
        border-radius: 0.375rem 0.375rem 0 0;
        transition: var(--transition);
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: var(--primary-color);
        background-color: rgba(67, 97, 238, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: #fff;
        border-bottom: 2px solid var(--primary-color);
    }

    /* Style pour les icônes */
    .fas {
        width: 1.25em;
        text-align: center;
    }

    /* Animation pour les champs requis */
    .form-control:required:not(:placeholder-shown):invalid {
        border-color: #e53e3e;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e53e3e' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e53e3e' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control:required:not(:placeholder-shown):valid {
        border-color: #38a169;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2338a169' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* Style pour les champs désactivés */
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 1;
    }

    /* Style pour les messages d'erreur */
    .invalid-feedback {
        font-size: 0.875rem;
        color: #e53e3e;
        margin-top: 0.25rem;
    }

    /* Style pour les champs avec erreur */
    .is-invalid {
        border-color: #e53e3e;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e53e3e' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e53e3e' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    /* Style pour les tooltips */
    .tooltip {
        font-size: 0.875rem;
    }

    /* Style pour les onglets responsives */
    @media (max-width: 767.98px) {
        .nav-tabs .nav-link {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn {
            padding: 0.4rem 1rem;
            font-size: 0.875rem;
        }
        
        .form-control, .form-select {
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    /* Animation de chargement */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 0.25rem solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
        margin-right: 0.5rem;
    }

    /* Style pour les champs de type range */
    .form-range::-webkit-slider-thumb {
        background: var(--primary-color);
    }

    .form-range::-moz-range-thumb {
        background: var(--primary-color);
    }

    .form-range::-ms-thumb {
        background: var(--primary-color);
    }

    .form-range:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .form-range:focus::-moz-range-thumb {
        box-shadow: 0 0 0 1px #fff, 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    /* Page-scoped: horizontal sidebar and two-column layout adjustments */
    .products-layout-horizontal-sidebar ~ .container .sidebar {
        /* no-op: just placeholder to indicate page scope if needed */
    }

    /* Force sidebar horizontal only on this page using a high-specificity selector */
    .products-layout-horizontal-sidebar .breadcrumb {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    /* Arrange main content in two columns: left (info + tarification) and right (configuration) */
    .products-layout-horizontal-sidebar .row.g-0 {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }

    .products-layout-horizontal-sidebar .col-lg-8 {
        flex: 0 0 65%;
        max-width: 65%;
    }

    .products-layout-horizontal-sidebar .col-lg-4 {
        flex: 0 0 35%;
        max-width: 35%;
    }

    /* Make sure the configuration card stays to the right and is vertically aligned */
    .products-layout-horizontal-sidebar .col-lg-4 {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* On small screens, stack back to column layout */
    @media (max-width: 991.98px) {
        .products-layout-horizontal-sidebar .col-lg-8,
        .products-layout-horizontal-sidebar .col-lg-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }

    /* Form enhancements: typography, spacing, layout */
    /* Container: center and constrain width for a professional layout */
    @media (min-width: 1200px) {
        .container-fluid .col-xl-10 {
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }
    }

    /* Headers */
    .card-header h4 {
        font-size: 20px;
        letter-spacing: -0.2px;
        font-weight: 700;
    }

    h5.text-primary {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    /* Form labels and inputs */
    .form-label {
        font-size: 14px;
        color: #394152;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        font-size: 15px;
        padding: 0.65rem 0.9rem;
        border-radius: 0.5rem;
    }

    .form-control::placeholder {
        color: #9aa4b2;
    }

    /* Group cards inside form to add visual separation */
    .card.border-0.bg-light, .card.border-0.bg-white {
        border-radius: 0.6rem;
        box-shadow: 0 6px 20px rgba(34,49,84,0.04);
    }

    .col-lg-8 p, .col-lg-4 p, .card .form-text {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* Spacing between field groups */
    .mb-3, .mb-4 {
        margin-bottom: 1rem !important;
    }

    /* Action buttons: more prominent and aligned */
    .form-actions {
        gap: 16px;
    }

    .form-actions .btn-primary {
        padding: 0.7rem 1.25rem;
        font-size: 16px;
        border-radius: 0.6rem;
    }

    .form-actions .btn-outline-secondary {
        padding: 0.65rem 1rem;
        border-radius: 0.6rem;
    }

    /* Improve alert/info panel inside pricing */
    .alert-info {
        border-left: 4px solid rgba(44,90,160,0.12);
        background: linear-gradient(90deg, rgba(67,97,238,0.03), rgba(255,255,255,0));
        padding: 0.9rem 1rem;
    }

    /* Responsive: stack columns and stretch buttons on small screens */
    @media (max-width: 991.98px) {
        .row.g-0 > .col-lg-8, .row.g-0 > .col-lg-4 {
            padding: 1rem !important;
            background: transparent !important;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions .btn {
            width: 100%;
        }
    }

    /* Focus and accessibility */
    .form-control:focus, .form-select:focus {
        outline: 3px solid rgba(44,90,160,0.08);
        outline-offset: 2px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const deviseSelect = document.getElementById('devise');
    const deviseSymbole = document.getElementById('devise-symbole');
    const tarifBaseInput = document.getElementById('tarif_base');
    const tauxInput = document.getElementById('taux');
    const tvaInput = document.getElementById('tva');
    const tarifTtcElement = document.getElementById('tarif-ttc');
    const paysSelect = document.getElementById('pays_id');
    const form = document.getElementById('produitForm');

    // Symboles des devises
    const symbolesDevises = {
        'USD': '$',
        'EUR': '€',
        'CDF': 'FC',
        'FBU': 'FB'
    };

    // Mettre à jour le symbole de la devise
    function updateDeviseSymbole() {
        const selectedDevise = deviseSelect.value;
        const symbole = symbolesDevises[selectedDevise] || selectedDevise;
        deviseSymbole.textContent = symbole;
        updateTarifTTC();
    }

    // Calculer le tarif TTC
    function updateTarifTTC() {
        const tarifBase = parseFloat(tarifBaseInput.value) || 0;
        const taux = parseFloat(tauxInput.value) || 0;
        const tva = parseFloat(tvaInput.value) || 0;
        
        // Calcul du montant avec taux
        const montantAvecTaux = tarifBase * (1 + (taux / 100));
        
        // Calcul du montant TTC
        const montantTTC = montantAvecTaux * (1 + (tva / 100));
        
        // Mise à jour de l'affichage
        if (tarifBase > 0) {
            tarifTtcElement.textContent = `${montantTTC.toFixed(2)} ${deviseSymbole.textContent}`;
        } else {
            tarifTtcElement.textContent = '-';
        }
    }

    // Récupérer les informations du pays sélectionné
    async function fetchPaysInfo(paysId) {
        if (!paysId) return;
        
        try {
            const response = await fetch(`/api/pays/${paysId}`);
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour la TVA si le champ est vide
                if (!tvaInput.value && data.pays.tva_par_defaut) {
                    tvaInput.value = data.pays.tva_par_defaut;
                }
                
                // Mettre à jour la devise si elle n'a pas été modifiée manuellement
                if (!deviseSelect.dataset.manuallyChanged && data.pays.devise) {
                    deviseSelect.value = data.pays.devise;
                    updateDeviseSymbole();
                }
                
                updateTarifTTC();
            }
        } catch (error) {
            console.error('Erreur lors de la récupération des informations du pays:', error);
        }
    }

    // Événements
    deviseSelect.addEventListener('change', function() {
        deviseSelect.dataset.manuallyChanged = 'true';
        updateDeviseSymbole();
    });

    [tarifBaseInput, tauxInput, tvaInput].forEach(input => {
        input.addEventListener('input', updateTarifTTC);
    });

    paysSelect.addEventListener('change', function() {
        fetchPaysInfo(this.value);
    });

    // Validation du formulaire
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);

    // Initialisation
    updateDeviseSymbole();
    
    // Charger les informations du pays si déjà sélectionné
    if (paysSelect.value) {
        fetchPaysInfo(paysSelect.value);
    }
});
</script>
@endpush