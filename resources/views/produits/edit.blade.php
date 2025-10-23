@extends('layouts.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('produits.index') }}">Produits</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier {{ $produit->nom }}</li>
                </ol>
            </nav>

            <!-- Formulaire produit -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier le Produit
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('produits.update', $produit) }}" method="POST" id="produitForm">
                        @csrf
                        @method('PUT')

                        <div class="card-container">
                            <div class="row w-100">
                                <!-- Informations générales -->
                                <div class="col-md-8">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="mb-0">
                                                <i class="fas fa-info-circle me-2"></i>Informations générales
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                                       id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                                          id="description" name="description" rows="3">{{ old('description', $produit->description) }}</textarea>
                                                <div class="form-text">Décrivez brièvement le produit et ses caractéristiques.</div>
                                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="couverture" class="form-label">Couverture <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('couverture') is-invalid @enderror" 
                                                          id="couverture" name="couverture" rows="3" required>{{ old('couverture', $produit->couverture) }}</textarea>
                                                <div class="form-text">Détaillez les garanties et conditions de couverture.</div>
                                                @error('couverture') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configuration & Tarification -->
                                <div class="col-md-4">
                                    <!-- Configuration -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configuration</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="pays_id" class="form-label">Pays <span class="text-danger">*</span></label>
                                                <select class="form-select @error('pays_id') is-invalid @enderror" id="pays_id" name="pays_id" required>
                                                    @foreach($pays as $p)
                                                        <option value="{{ $p->id }}" {{ old('pays_id', $produit->pays_id) == $p->id ? 'selected' : '' }}>
                                                            {{ $p->nom }} ({{ $p->code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('pays_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="devise" class="form-label">Devise <span class="text-danger">*</span></label>
                                                <select class="form-select @error('devise') is-invalid @enderror" id="devise" name="devise" required>
                                                    @foreach($devises as $code => $label)
                                                        <option value="{{ $code }}" {{ old('devise', $produit->devise) == $code ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('devise') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" id="actif" name="actif" value="1" {{ old('actif', $produit->actif) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="actif">Produit actif</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tarification -->
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Tarification</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="tarif_base" class="form-label">Tarif de base <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0" class="form-control @error('tarif_base') is-invalid @enderror" id="tarif_base" name="tarif_base" value="{{ old('tarif_base', $produit->tarif_base) }}" required>
                                                    <span class="input-group-text" id="devise-symbole">FC</span>
                                                    @error('tarif_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="duree" class="form-label">Durée</label>
                                                <input type="text" class="form-control @error('duree') is-invalid @enderror" id="duree" name="duree" value="{{ old('duree', $produit->duree) }}" placeholder="Ex: 12 mois, 1 an, 30 jours">
                                                <div class="form-text">Indiquez la durée de la couverture (ex: 1 an, 12 mois).</div>
                                                @error('duree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="taux" class="form-label">Taux spécifique (%)</label>
                                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('taux') is-invalid @enderror" id="taux" name="taux" value="{{ old('taux', $produit->taux) }}">
                                                <div class="form-text">Laissez vide pour utiliser le taux par défaut du pays.</div>
                                                @error('taux') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="tva" class="form-label">TVA (%)</label>
                                                <input type="number" step="0.01" min="0" max="100" class="form-control @error('tva') is-invalid @enderror" id="tva" name="tva" value="{{ old('tva', $produit->tva) }}">
                                                <div class="form-text">Laissez vide pour utiliser la TVA par défaut du pays.</div>
                                                @error('tva') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="alert alert-info p-2 mb-0">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-info-circle me-2"></i>
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
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('produits.show', $produit) }}" class="btn btn-light"><i class="fas fa-arrow-left me-1"></i> Annuler</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Enregistrer les modifications</button>
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
    const deviseSelect = document.getElementById('devise');
    const deviseSymbole = document.getElementById('devise-symbole');
    const tarifBaseInput = document.getElementById('tarif_base');
    const dureeSelect = document.getElementById('duree');
    const tauxInput = document.getElementById('taux');
    const tvaInput = document.getElementById('tva');
    const tarifTtcElement = document.getElementById('tarif-ttc');
    const symbolesDevises = { 'USD':'$', 'EUR':'€', 'BIF':'FBu', 'CDF':'FC', 'XAF':'FCFA' };

    function updateDeviseSymbole() {
        deviseSymbole.textContent = symbolesDevises[deviseSelect.value] || deviseSelect.value;
        updateTarifTTC();
    }

    function updateTarifTTC() {
        const tarifBase = parseFloat(tarifBaseInput.value) || 0;
        const taux = parseFloat(tauxInput.value) || 0;
        const tva = parseFloat(tvaInput.value) || 0;
        const duree = parseInt(dureeSelect ? dureeSelect.value : 12) || 12; // months
        const durationFactor = duree / 12;

        // Calcul du montant avec taux
        const montantAvecTaux = tarifBase * (1 + (taux / 100));

        // Appliquer la durée (proportionnelle à l'année)
        const montantPourLaDuree = montantAvecTaux * durationFactor;

        // Calcul du montant TTC
        const montantTTC = montantPourLaDuree * (1 + (tva / 100));

        tarifTtcElement.textContent = tarifBase > 0 ? `${montantTTC.toFixed(2).replace('.', ',')} ${deviseSymbole.textContent}` : '-';
    }

    [tarifBaseInput, tauxInput, tvaInput].forEach(input => input.addEventListener('input', updateTarifTTC));
    if (dureeSelect) { dureeSelect.addEventListener('change', updateTarifTTC); }
    deviseSelect.addEventListener('change', updateDeviseSymbole);
    updateDeviseSymbole();
});
</script>
@endpush

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
    color: darken(var(--accent), 8%);
    text-decoration: underline;
}

/* Ensure horizontal alignment of header badges & timestamp */
.products-show-card .d-flex.align-items-center { gap: .5rem; }
</style>
@endpush
