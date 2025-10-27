@extends('layouts.app')

@section('title', 'Configuration Système')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs"></i> Configuration Système
        </h1>
    </div>

    <!-- Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Onglets -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">
                    <i class="fas fa-info-circle"></i> Général
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="notifications-tab" data-toggle="tab" href="#notifications" role="tab">
                    <i class="fas fa-bell"></i> Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab">
                    <i class="fas fa-shield-alt"></i> Sécurité
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="backup-tab" data-toggle="tab" href="#backup" role="tab">
                    <i class="fas fa-database"></i> Sauvegarde
                </a>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabContent">
            <!-- Onglet Général -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Paramètres Généraux</h6>
                    </div>
                    <div class="card-body">
                        @foreach($generalSettings as $setting)
                            <div class="form-group">
                                <label for="{{ $setting->key }}">
                                    {{ ucfirst(str_replace('_', ' ', str_replace('app_', '', $setting->key))) }}
                                    @if($setting->description)
                                        <small class="text-muted">({{ $setting->description }})</small>
                                    @endif
                                </label>

                                @if($setting->type === 'file')
                                    @if($setting->value)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $setting->value) }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" 
                                           class="form-control-file" 
                                           name="file_{{ $setting->key }}" 
                                           id="{{ $setting->key }}"
                                           accept="image/*">
                                    <input type="hidden" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                                
                                @elseif($setting->type === 'boolean')
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               name="settings[{{ $setting->key }}]" 
                                               id="{{ $setting->key }}"
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $setting->key }}">Activé</label>
                                    </div>
                                
                                @elseif($setting->type === 'json')
                                    @php
                                        $values = json_decode($setting->value, true) ?? [];
                                    @endphp
                                    <select class="form-control" 
                                            name="settings[{{ $setting->key }}][]" 
                                            id="{{ $setting->key }}"
                                            multiple>
                                        <option value="USD" {{ in_array('USD', $values) ? 'selected' : '' }}>USD - Dollar Américain</option>
                                        <option value="CDF" {{ in_array('CDF', $values) ? 'selected' : '' }}>CDF - Franc Congolais</option>
                                        <option value="EUR" {{ in_array('EUR', $values) ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                
                                @elseif($setting->key === 'default_currency')
                                    <select class="form-control" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}">
                                        <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>USD - Dollar Américain</option>
                                        <option value="CDF" {{ $setting->value === 'CDF' ? 'selected' : '' }}>CDF - Franc Congolais</option>
                                        <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                    </select>
                                
                                @else
                                    <input type="text" 
                                           class="form-control" 
                                           name="settings[{{ $setting->key }}]" 
                                           id="{{ $setting->key }}"
                                           value="{{ $setting->value }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Onglet Notifications -->
            <div class="tab-pane fade" id="notifications" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Paramètres de Notifications</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-info mb-3">
                                    <i class="fas fa-envelope"></i> Notifications Email
                                </h6>
                                @foreach($notificationSettings->where('key', 'like', 'email%') as $setting)
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   id="{{ $setting->key }}"
                                                   value="1"
                                                   {{ $setting->value ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="{{ $setting->key }}">
                                                {{ $setting->description }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                                <hr>
                                <h6 class="font-weight-bold mb-3">Test Email</h6>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="test_email" placeholder="email@exemple.com">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info" onclick="testEmail()">
                                            <i class="fas fa-paper-plane"></i> Envoyer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="font-weight-bold text-success mb-3">
                                    <i class="fas fa-sms"></i> Notifications SMS
                                </h6>
                                @foreach($notificationSettings->where('key', 'like', 'sms%') as $setting)
                                    <div class="form-group">
                                        <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                        @if($setting->type === 'boolean')
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" 
                                                       class="custom-control-input" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       id="{{ $setting->key }}"
                                                       value="1"
                                                       {{ $setting->value ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="{{ $setting->key }}">Activé</label>
                                            </div>
                                        @else
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   id="{{ $setting->key }}"
                                                   value="{{ $setting->value }}"
                                                   placeholder="{{ $setting->description }}">
                                        @endif
                                    </div>
                                @endforeach

                                <hr>
                                <h6 class="font-weight-bold mb-3">Test SMS</h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="test_phone" placeholder="+243 000 000 000">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" onclick="testSms()">
                                            <i class="fas fa-mobile-alt"></i> Envoyer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-bell"></i> Notifications In-App
                        </h6>
                        @foreach($notificationSettings->where('key', 'like', 'inapp%') as $setting)
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           name="settings[{{ $setting->key }}]" 
                                           id="{{ $setting->key }}"
                                           value="1"
                                           {{ $setting->value ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{ $setting->key }}">
                                        {{ $setting->description }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Onglet Sécurité -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Paramètres de Sécurité</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="font-weight-bold text-warning mb-3">
                            <i class="fas fa-key"></i> Politique de Mot de Passe
                        </h6>
                        
                        @foreach($securitySettings->where('key', 'like', 'password%') as $setting)
                            <div class="form-group">
                                <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                @if($setting->type === 'boolean')
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               name="settings[{{ $setting->key }}]" 
                                               id="{{ $setting->key }}"
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $setting->key }}">Activé</label>
                                    </div>
                                @else
                                    <input type="number" 
                                           class="form-control" 
                                           name="settings[{{ $setting->key }}]" 
                                           id="{{ $setting->key }}"
                                           value="{{ $setting->value }}"
                                           min="6"
                                           max="32">
                                @endif
                            </div>
                        @endforeach

                        <hr>

                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="fas fa-user-lock"></i> Sécurité des Sessions
                        </h6>
                        
                        @foreach($securitySettings->whereNotIn('key', function($query) {
                            return $query->where('key', 'like', 'password%');
                        }) as $setting)
                            <div class="form-group">
                                <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                @if($setting->type === 'boolean')
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               name="settings[{{ $setting->key }}]" 
                                               id="{{ $setting->key }}"
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $setting->key }}">Activé</label>
                                    </div>
                                @else
                                    <input type="number" 
                                           class="form-control" 
                                           name="settings[{{ $setting->key }}]" 
                                           id="{{ $setting->key }}"
                                           value="{{ $setting->value }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Onglet Sauvegarde -->
            <div class="tab-pane fade" id="backup" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Paramètres de Sauvegarde</h6>
                    </div>
                    <div class="card-body">
                        @foreach($backupSettings as $setting)
                            <div class="form-group">
                                <label for="{{ $setting->key }}">{{ $setting->description }}</label>
                                @if($setting->type === 'boolean')
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               name="settings[{{ $setting->key }}]" 
                                               id="{{ $setting->key }}"
                                               value="1"
                                               {{ $setting->value ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $setting->key }}">Activé</label>
                                    </div>
                                @elseif($setting->key === 'backup_frequency')
                                    <select class="form-control" name="settings[{{ $setting->key }}]" id="{{ $setting->key }}">
                                        <option value="daily" {{ $setting->value === 'daily' ? 'selected' : '' }}>Quotidienne</option>
                                        <option value="weekly" {{ $setting->value === 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                                        <option value="monthly" {{ $setting->value === 'monthly' ? 'selected' : '' }}>Mensuelle</option>
                                    </select>
                                @else
                                    <input type="number" 
                                           class="form-control" 
                                           name="settings[{{ $setting->key }}]" 
                                           id="{{ $setting->key }}"
                                           value="{{ $setting->value }}">
                                @endif
                            </div>
                        @endforeach

                        <hr>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Gestion des sauvegardes :</strong>
                            Pour créer, télécharger ou restaurer des sauvegardes, rendez-vous sur la 
                            <a href="{{ route('admin.backups.index') }}" class="alert-link">page de gestion des sauvegardes</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer les paramètres
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function testEmail() {
    const email = document.getElementById('test_email').value;
    if (!email) {
        alert('Veuillez saisir une adresse email');
        return;
    }
    
    fetch('{{ route("admin.settings.test-email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ test_email: email })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'Email envoyé avec succès');
    })
    .catch(error => {
        alert('Erreur lors de l\'envoi de l\'email');
    });
}

function testSms() {
    const phone = document.getElementById('test_phone').value;
    if (!phone) {
        alert('Veuillez saisir un numéro de téléphone');
        return;
    }
    
    fetch('{{ route("admin.settings.test-sms") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ test_phone: phone })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message || 'SMS envoyé avec succès');
    })
    .catch(error => {
        alert('Erreur lors de l\'envoi du SMS');
    });
}
</script>
@endpush
