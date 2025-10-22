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
        const montantTTC = tarifBase * (1 + (taux/100)) * (1 + (tva/100));
        tarifTtcElement.textContent = tarifBase > 0 ? `${montantTTC.toFixed(2).replace('.', ',')} ${deviseSymbole.textContent}` : '-';
    }

    [tarifBaseInput, tauxInput, tvaInput].forEach(input => input.addEventListener('input', updateTarifTTC));
    deviseSelect.addEventListener('change', updateDeviseSymbole);
    updateDeviseSymbole();
});
</script>
@endpush

@push('styles')
<style>
body { color:#c9d1d9; font-family:'Poppins',sans-serif; }
.breadcrumb { padding:0; margin-bottom:1rem; font-size:0.9rem; color:#8b949e; display:flex; justify-content:center; gap:80px;}
.breadcrumb-item a { color:#58a6ff; text-decoration:none; text-transform:capitalize; }
.breadcrumb-item.active { color:#8b949e; }

.card-container { display:flex; flex-wrap:wrap; justify-content:center; gap:20px; margin-top:20px; }
.card-container > .row { width:100%; }
.card { flex:1 1 calc(50% - 20px); max-width:600px; background:rgba(255,255,255,0.85); border-radius:1rem; box-shadow:0 4px 20px rgba(0,0,0,0.4); transition:0.3s; padding:0; margin-bottom:20px;}
.card:hover { transform:translateY(-3px); box-shadow:0 6px 25px rgba(0,0,0,0.5); }
.card-header { background:rgba(255,255,255,0.05); border-bottom:1px solid rgba(255,255,255,0.08); color:#58a6ff; font-weight:600; }
.form-control, .form-select { background:#fdfdfd; color:#161b22; border-radius:0.5rem; transition:0.3s; }
.form-control:focus, .form-select:focus { border-color:#58a6ff; box-shadow:0 0 0 0.2rem rgba(88,166,255,0.2); }
label { color:#161b22; font-weight:500; }
.btn-primary { background:linear-gradient(90deg,#238636,#2ea043); border:none; color:#fff; font-weight:600; border-radius:0.5rem; padding:0.6rem 1.2rem; }
.btn-primary:hover { background:linear-gradient(90deg,#2ea043,#3fb950); transform:scale(1.03); }
.alert-info { background: rgba(56,139,253,0.1); border:1px solid rgba(56,139,253,0.3); color:#58a6ff; border-radius:0.6rem; }
#tarif-ttc { font-weight:700; color:#58a6ff; font-size:1.3rem; }
</style>
@endpush
