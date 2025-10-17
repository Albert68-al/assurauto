@extends('layouts.app')

@section('title', 'Nouveau Produit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nouveau produit</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Nouveau Produit
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('produits.store') }}" method="POST" id="produitForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>Informations générales
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="nom" class="form-label">
                                                Nom du produit <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                   id="nom" name="nom" value="{{ old('nom') }}" required>
                                            @error('nom')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            <div class="form-text">Décrivez brièvement le produit et ses caractéristiques.</div>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="couverture" class="form-label">
                                                Couverture <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('couverture') is-invalid @enderror" 
                                                    id="couverture" name="couverture" rows="3" required>{{ old('couverture') }}</textarea>
                                            <div class="form-text">Détaillez les garanties et conditions de couverture.</div>
                                            @error('couverture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-cog me-2"></i>Configuration
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="pays_id" class="form-label">
                                                Pays <span class="text-danger">*</span>
                                            </label>
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
                                            <label for="devise" class="form-label">
                                                Devise <span class="text-danger">*</span>
                                            </label>
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

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="actif" name="actif" value="1" 
                                                   {{ old('actif', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="actif">
                                                Produit actif
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-calculator me-2"></i>Tarification
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="tarif_base" class="form-label">
                                                Tarif de base <span class="text-danger">*</span>
                                            </label>
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
                                            <label for="taux" class="form-label">
                                                Taux spécifique (%)
                                            </label>
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   class="form-control @error('taux') is-invalid @enderror" 
                                                   id="taux" name="taux" 
                                                   value="{{ old('taux', 0) }}">
                                            <div class="form-text">Laissez vide pour utiliser le taux par défaut du pays.</div>
                                            @error('taux')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="tva" class="form-label">
                                                TVA (%)
                                            </label>
                                            <input type="number" step="0.01" min="0" max="100" 
                                                   class="form-control @error('tva') is-invalid @enderror" 
                                                   id="tva" name="tva" 
                                                   value="{{ old('tva') }}">
                                            <div class="form-text">Laissez vide pour utiliser la TVA par défaut du pays.</div>
                                            @error('tva')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="alert alert-info p-2 mb-0">
                                            <div class="d-flex">
                                                <i class="fas fa-info-circle mt-1 me-2"></i>
                                                <div>
                                                    <strong>Tarif TTC estimé :</strong>
                                                    <div id="tarif-ttc" class="h5 mb-0">-</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('produits.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Enregistrer le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mettre à jour le symbole de la devise
        const deviseSelect = document.getElementById('devise');
        const deviseSymbole = document.getElementById('devise-symbole');
        
        // Symboles des devises
        const symbolesDevises = {
            'USD': '$',
            'EUR': '€',
            'XAF': 'FCFA',
            'CDF': 'FC'
        };
        
        // Mettre à jour le symbole quand la devise change
        function updateDeviseSymbole() {
            const codeDevise = deviseSelect.value;
            deviseSymbole.textContent = symbolesDevises[codeDevise] || codeDevise;
            updateTarifTTC();
        }
        
        deviseSelect.addEventListener('change', updateDeviseSymbole);
        
        // Calculer le tarif TTC
        const tarifBaseInput = document.getElementById('tarif_base');
        const tauxInput = document.getElementById('taux');
        const tvaInput = document.getElementById('tva');
        const tarifTtcElement = document.getElementById('tarif-ttc');
        
        function updateTarifTTC() {
            const tarifBase = parseFloat(tarifBaseInput.value) || 0;
            const taux = parseFloat(tauxInput.value) || 0;
            const tva = parseFloat(tvaInput.value) || 0;
            
            const montantAvecTaux = tarifBase * (1 + (taux / 100));
            const montantTTC = montantAvecTaux * (1 + (tva / 100));
            
            if (tarifBase > 0) {
                tarifTtcElement.textContent = `${montantTTC.toFixed(2)} ${deviseSymbole.textContent}`;
            } else {
                tarifTtcElement.textContent = '-';
            }
        }
        
        // Écouter les changements sur les champs de prix
        [tarifBaseInput, tauxInput, tvaInput].forEach(input => {
            input.addEventListener('input', updateTarifTTC);
        });
        
        // Initialiser les valeurs
        updateDeviseSymbole();
        
        // Récupérer la TVA par défaut du pays sélectionné
        const paysSelect = document.getElementById('pays_id');
        
        paysSelect.addEventListener('change', function() {
            const paysId = this.value;
            
            if (paysId) {
                fetch(`/api/pays/${paysId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && !tvaInput.value) {
                            tvaInput.value = data.pays.tva_par_defaut;
                            updateTarifTTC();
                        }
                        
                        // Mettre à jour la devise si elle n'a pas été modifiée manuellement
                        if (!deviseSelect.dataset.changed) {
                            deviseSelect.value = data.pays.devise;
                            updateDeviseSymbole();
                        }
                    });
            }
        });
        
        // Marquer si la devise a été modifiée manuellement
        deviseSelect.addEventListener('change', function() {
            this.dataset.changed = true;
        });
        
        // Validation du formulaire
        const form = document.getElementById('produitForm');
        
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endpush

@push('styles')
<style>
    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        margin-bottom: 1.5rem;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.25rem;
    }
    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    .form-text {
        font-size: 0.8rem;
        color: #6c757d;
    }
    #tarif-ttc {
        color: #0d6efd;
        font-weight: 600;
    }
</style>
@endpush
