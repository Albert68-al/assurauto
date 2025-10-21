@extends('layouts.app')

@section('title', 'Module COMESA')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white px-3 py-2 rounded-3 shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Module COMESA</li>
                </ol>
            </nav>

            <!-- Header Section -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3 text-center">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 text-primary">Module COMESA</h3>
                            <small class="text-muted">Gestion multi-pays, taux locaux et règles régionales</small>
                        </div>
                        <div>
                            <button id="saveAllBtn" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Enregistrer tout
                            </button>
                        </div>
                    </div>
                        </div>

                        <!-- Règles Régionales Card (moved to left column) -->
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-header bg-warning text-dark py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-gavel me-2"></i>Règles régionales
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Définissez ici des règles qui s'appliquent lorsque la police couvre plusieurs pays COMESA.</p>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Règles régionales</label>
                                    <textarea id="regionalRules_left" class="form-control" rows="5" placeholder='{"règle1":"..."}'>{

}</textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button id="saveRulesBtn_left" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i>Enregistrer règles
                                    </button>
                                </div>
                            </div>
                        </div>
            </div>

            <!-- Main Content Grid - 2 columns layout -->
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-6">
                    <!-- Configuration Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Configuration par pays
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                                <div class="mb-3 mb-md-0">
                                    <h6 class="text-dark mb-2">Sélectionner un pays</h6>
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <select id="countrySelect" class="form-select" style="width:220px">
                                            <option value="">-- Choisir un pays --</option>
                                            @foreach(\App\Models\Pays::orderBy('nom')->get() as $p)
                                                <option value="{{ $p->id }}">{{ $p->nom }} ({{ $p->code }})</option>
                                            @endforeach
                                        </select>
                                        <button id="addConfigBtn" class="btn btn-outline-primary">
                                            <i class="fas fa-plus me-2"></i>Ajouter
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Country badges grid -->
                            <div class="mb-4">
                                <h6 class="text-dark mb-3">Pays disponibles</h6>
                                <div id="countryBadges" class="country-badges">
                                    @foreach(\App\Models\Pays::orderBy('nom')->get() as $p)
                                        <div class="badge-item" data-id="{{ $p->id }}">
                                            <span>{{ $p->nom }} ({{ $p->code }})</span>
                                            <i class="fas fa-check ms-2"></i>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Configurations Table -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="configsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Pays</th>
                                            <th style="width:90px">Taux (%)</th>
                                            <th style="width:90px">TVA (%)</th>
                                            <th style="width:120px" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- rows generated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-6">
                    <!-- Taux Locaux Card -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-line me-2"></i>Taux locaux
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="text-muted mb-4">
                                Gérer localement les taux de change (simule une mise à jour automatique).
                            </p>
                            
                            <div id="ratesList" class="rates-container flex-grow-1 mb-4">
                                <!-- Les taux seront générés ici -->
                            </div>
                            
                            <div class="rates-actions mt-auto">
                                <button id="addRateBtn" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-plus me-2"></i>Ajouter un taux
                                </button>
                                <button id="saveRatesBtn" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les taux
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Configuration Card (right column, below Taux locaux) -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-flag me-2"></i>Éditer la configuration du pays
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Editez directement les paramètres du pays sélectionné.</p>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pays</label>
                                <input type="text" id="edit_country_name" class="form-control text-center fw-semibold bg-white border-0 shadow-sm" readonly>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold">Taux (%)</label>
                                    <input type="number" id="edit_taux" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold">TVA (%)</label>
                                    <input type="number" id="edit_tva" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label fw-semibold">Législation</label>
                                <textarea id="edit_legislation" class="form-control" rows="4" placeholder="Notes législatives..."></textarea>
                            </div>
                            <input type="hidden" id="edit_country_id">
                            <div class="d-flex justify-content-end mt-3 gap-2">
                                <button id="editCancelBtn" class="btn btn-outline-secondary">Annuler</button>
                                <button id="saveEditBtn" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal removed: in-page editor used instead -->

@push('styles')
<style>
/* ===== COMESA MODULE UI PRO ===== */
:root {
    --primary-color: #2563eb;
    --primary-dark: #1e40af;
    --secondary-color: #64748b;
    --light-bg: #f8fafc;
    --border-color: #e2e8f0;
    --success-color: #10b981;
    --warning-color: #f59e0b;
}

body {
    background: #f7f9fb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    border: none;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.12);
}

.card-header {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
    padding: 1rem 1.25rem;
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #2563eb, #1e40af) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #10b981, #059669) !important;
}

.card-header.bg-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
}

.card-header h5 {
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 0;
}

.breadcrumb {
    background: #fff;
    border-radius: 10px;
    font-size: 0.9rem;
    padding: 0.75rem 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.breadcrumb-item a {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 500;
}

.breadcrumb-item.active {
    color: var(--secondary-color);
}

/* Country badges grid: 1 column on mobile, 2 columns on md+ */
.country-badges {
    display: grid;
    grid-template-columns: 1fr; /* mobile: single column */
    gap: 12px;
    max-height: 420px;
    overflow-y: auto;
    padding: 5px;
}
@media (min-width: 768px) {
    .country-badges {
        grid-template-columns: repeat(2, 1fr); /* two per row */
    }
}

.country-badges .badge-item {
    background: #fff;
    border: 1px solid var(--border-color);
    padding: 10px 12px;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    font-weight: 600;
    color: #0f172a;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.03);
    font-size: 0.9rem;
}

.country-badges .badge-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: var(--primary-color);
}

.country-badges .badge-item.configured {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: #fff;
    border: none;
}

.country-badges .badge-item i {
    opacity: 0;
    transition: opacity 0.2s ease;
    font-size: 0.8rem;
}

.country-badges .badge-item.configured i {
    opacity: 1;
}

/* Table styling */
.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table th {
    background: #f1f5f9;
    font-weight: 700;
    color: #1e3a8a;
    padding: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
    text-align: center;
    font-size: 0.85rem;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    text-align: center;
    font-size: 0.85rem;
}

.table-hover tbody tr:hover {
    background: rgba(37, 99, 235, 0.05);
    transition: all 0.2s ease;
}

/* Buttons */
.btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border: none;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.4);
}

.btn-outline-primary {
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: #fff;
    transform: translateY(-2px);
}

/* Modal improvements */
.modal-content {
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border: none;
    animation: slideUp 0.4s ease;
}

.modal-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    padding: 1.25rem 1.5rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #2563eb, #1e40af) !important;
}

/* Rates container */
.rates-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 150px;
}

.rate-row {
    display: flex;
    gap: 10px;
    align-items: center;
    background: white;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.rate-row input {
    flex: 1;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
}

.rates-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: auto;
}

/* Form improvements */
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid var(--border-color);
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Animations */
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .country-badges {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
    
    .rate-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .rates-actions {
        flex-direction: column;
    }
    
    .card-header h5 {
        font-size: 1rem;
    }
    
    .table th, .table td {
        font-size: 0.8rem;
        padding: 0.5rem;
    }
}

@media (max-width: 576px) {
    .container-fluid .py-4 {
        padding: 1rem 0.5rem !important;
    }
    
    .breadcrumb {
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const countrySelect = document.getElementById('countrySelect');
    const saveAllBtn = document.getElementById('saveAllBtn');
    const saveRulesBtn = document.getElementById('saveRulesBtn_left');

    // in-page editor fields (replaces modal workflow)
    const editCountryName = document.getElementById('edit_country_name');
    const editTaux = document.getElementById('edit_taux');
    const editTva = document.getElementById('edit_tva');
    const editLeg = document.getElementById('edit_legislation');
    const editCountryId = document.getElementById('edit_country_id');
    const saveEditBtn = document.getElementById('saveEditBtn');
    const editCancelBtn = document.getElementById('editCancelBtn');

    async function loadConfig(){
        const res = await fetch('{{ route('comesa.config.get') }}');
        const data = await res.json();
        window.comesaConfig = data.config || {};
    }

    async function loadRates(){
        const res = await fetch('{{ route('comesa.rates.get') }}');
        const data = await res.json();
        window.comesaRates = data.rates || {};
        renderRates();
    }

    function renderRates(){
        const container = document.getElementById('ratesList');
        container.innerHTML = '';
        const rates = window.comesaRates || {};
        
        if (Object.keys(rates).length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-4">Aucun taux configuré. Cliquez sur "Ajouter un taux" pour commencer.</p>';
            return;
        }
        
        for(const [key, val] of Object.entries(rates)){
            const row = document.createElement('div');
            row.className = 'rate-row';
            row.innerHTML = `
                <input class="form-control" placeholder="Code devise" value="${key}" readonly style="max-width: 120px;">
                <input class="form-control" data-currency="${key}" value="${val}" placeholder="Taux" type="number" step="0.0001">
                <button class="btn btn-sm btn-danger remove-rate" type="button">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(row);
        }
    }

    // map of pays id -> name/code
    const paysMap = {
        @foreach(\App\Models\Pays::orderBy('nom')->get() as $p)
            '{{ $p->id }}': { name: '{{ addslashes($p->nom) }}', code: '{{ $p->code }}' },
        @endforeach
    };

    function renderConfigs(){
        const tbody = document.querySelector('#configsTable tbody');
        tbody.innerHTML = '';
        const cfg = window.comesaConfig || {};
        
        if (Object.keys(cfg).length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        Aucune configuration de pays. Sélectionnez un pays et cliquez sur "Ajouter".
                    </td>
                </tr>
            `;
            return;
        }
        
        for(const [id, data] of Object.entries(cfg)){
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="ps-3">
                    <div class="d-flex align-items-center">
                        <span class="fw-semibold">${paysMap[id] ? paysMap[id].name : id}</span>
                        <span class="text-muted ms-2">(${paysMap[id] ? paysMap[id].code : ''})</span>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark">${data.taux ?? '0'}</span></td>
                <td><span class="badge bg-light text-dark">${data.tva ?? '0'}</span></td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-primary edit-config" data-id="${id}" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger delete-config" data-id="${id}" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        }
        // mark badges as configured
        const badges = document.querySelectorAll('#countryBadges .badge-item');
        badges.forEach(b => {
            const id = b.dataset.id;
            if(window.comesaConfig && window.comesaConfig[id]) {
                b.classList.add('configured');
            } else {
                b.classList.remove('configured');
            }
        });
    }

    // open in-page editor helper
    function openEditForCountry(id){
        const item = window.comesaConfig[id] || {};
        editCountryId.value = id;
        editCountryName.value = paysMap[id] ? paysMap[id].name + ' ('+paysMap[id].code+')' : id;
        editTaux.value = item.taux || '';
        editTva.value = item.tva || '';
        editLeg.value = item.legislation || '';
        // bring editor into view
        editCountryName.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // events: add rate
    document.getElementById('addRateBtn').addEventListener('click', function(){
        const container = document.getElementById('ratesList');
        // Remove empty message if present
        if (container.querySelector('.text-muted')) {
            container.innerHTML = '';
        }
        const row = document.createElement('div');
        row.className = 'rate-row';
        row.innerHTML = `
            <input class="form-control" placeholder="Code devise (ex: USD)" value="" style="max-width: 120px;">
            <input class="form-control" placeholder="Taux (ex: 1.05)" value="" type="number" step="0.0001">
            <button class="btn btn-sm btn-danger remove-rate" type="button">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(row);
    });

    // save rates
    document.getElementById('saveRatesBtn').addEventListener('click', async function(){
        const rows = Array.from(document.querySelectorAll('#ratesList .rate-row'));
        const out = {};
        let hasError = false;
        
        rows.forEach(r => {
            const inputs = r.querySelectorAll('input');
            const key = inputs[0].value.trim().toUpperCase();
            const val = parseFloat(inputs[1].value);
            
            if (!key) {
                alert('Veuillez saisir un code devise valide');
                hasError = true;
                return;
            }
            
            if (isNaN(val)) {
                alert('Veuillez saisir un taux valide');
                hasError = true;
                return;
            }
            
            out[key] = val;
        });
        
        if (hasError) return;
        
        await fetch('{{ route('comesa.rates.save') }}', { 
            method: 'POST', 
            headers: { 
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            }, 
            body: JSON.stringify(out)
        });
        
        alert('Taux sauvegardés localement.');
        loadRates();
    });

    // remove rate
    document.body.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-rate') || e.target.closest('.remove-rate')){
            e.target.closest('.rate-row').remove();
        }
    });

    // add config (opens in-page editor to edit right away)
    document.getElementById('addConfigBtn').addEventListener('click', function(){
        const id = document.getElementById('countrySelect').value;
        if(!id){ 
            alert('Choisissez un pays'); 
            return; 
        }
        window.comesaConfig = window.comesaConfig || {};
        if(!window.comesaConfig[id]){
            window.comesaConfig[id] = { taux: 0, tva: 0, legislation: '' };
        }
        renderConfigs();
        openEditForCountry(id);
    });

    // table actions (edit/delete)
    document.querySelector('#configsTable tbody').addEventListener('click', function(e){
        const edit = e.target.closest('.edit-config');
        const del = e.target.closest('.delete-config');
        if(edit){
            const id = edit.dataset.id;
            openEditForCountry(id);
        }
        if(del){
            const id = del.dataset.id;
            if(confirm('Supprimer la configuration pour ce pays ?')){
                delete window.comesaConfig[id];
                awaitSaveConfig();
            }
        }
    });

    // badge click opens modal
    document.getElementById('countryBadges').addEventListener('click', function(e){
        const b = e.target.closest('.badge-item');
        if(!b) return;
        const id = b.dataset.id;
        // ensure config exists
        window.comesaConfig = window.comesaConfig || {};
        if(!window.comesaConfig[id]) {
            window.comesaConfig[id] = { taux: 0, tva: 0, legislation: '' };
        }
        renderConfigs();
        openEditForCountry(id);
    });

    async function awaitSaveConfig(){
        await fetch('{{ route('comesa.config.save') }}', { 
            method:'POST', 
            headers:{ 
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}' 
            }, 
            body: JSON.stringify(window.comesaConfig || {}) 
        });
        await loadConfig();
        renderConfigs();
    }

    // in-page editor save
    saveEditBtn.addEventListener('click', function(){
        const id = editCountryId.value;
        if(!id) { alert('Aucun pays sélectionné'); return; }
        window.comesaConfig = window.comesaConfig || {};
        window.comesaConfig[id] = { 
            taux: parseFloat(editTaux.value) || 0, 
            tva: parseFloat(editTva.value) || 0, 
            legislation: editLeg.value 
        };
        awaitSaveConfig();
        alert('Configuration sauvegardée.');
    });

    // cancel edits
    editCancelBtn.addEventListener('click', function(){
        editCountryId.value = '';
        editCountryName.value = '';
        editTaux.value = '';
        editTva.value = '';
        editLeg.value = '';
    });

    // save rules
    saveRulesBtn.addEventListener('click', async function(){
        const rules = document.getElementById('regionalRules_left').value;
        try {
            JSON.parse(rules); // Validate JSON
            await fetch('{{ route('comesa.rules.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ rules: rules })
            });
            alert('Règles sauvegardées avec succès.');
        } catch (e) {
            alert('Erreur: Le format JSON est invalide.');
        }
    });

    // save all
    saveAllBtn.addEventListener('click', async function(){
        await awaitSaveConfig();
        
        // Save rates
        const rows = Array.from(document.querySelectorAll('#ratesList .rate-row'));
        const ratesOut = {};
        rows.forEach(r => {
            const inputs = r.querySelectorAll('input');
            const key = inputs[0].value.trim().toUpperCase();
            const val = parseFloat(inputs[1].value) || 0;
            if(key) ratesOut[key] = val;
        });
        await fetch('{{ route('comesa.rates.save') }}', { 
            method: 'POST', 
            headers: { 
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}'
            }, 
            body: JSON.stringify(ratesOut)
        });
        
        // Save rules
        const rules = document.getElementById('regionalRules_left').value;
        try {
            JSON.parse(rules);
            await fetch('{{ route('comesa.rules.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ rules: rules })
            });
        } catch (e) {
            alert('Attention: Les règles n\'ont pas été sauvegardées - format JSON invalide.');
        }
        
        alert('Toutes les données ont été sauvegardées.');
    });

    // initialize
    (async function(){
        await loadConfig();
        renderConfigs();
        await loadRates();
    })();
});
</script>
@endpush
@endsection