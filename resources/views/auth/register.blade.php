@extends('layouts.auth')

@section('content')
<div class="login-container">
    <div class="login-background">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>

    <div class="login-card">
        <div class="login-header">
            <div class="logo animate-logo">
                <i class="fas fa-car-crash"></i>
                <span class="logo-text">Assur</span><span class="logo-text accent">Auto</span>
            </div>
            <h1>Inscription</h1>
            <p class="login-subtitle">Créez votre compte en quelques étapes</p>
        </div>

        @if($errors->any())
            <div class="form-error error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
            @csrf
            
            <!-- Étape 1 -->
            <div id="registerStep1">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" required 
                               value="{{ old('name') }}"
                               placeholder="Votre nom complet"
                               class="@error('name') is-invalid @enderror">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required 
                               value="{{ old('email') }}"
                               placeholder="votre@email.com"
                               class="@error('email') is-invalid @enderror">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required 
                               placeholder="Créez un mot de passe"
                               class="@error('password') is-invalid @enderror">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password_confirmation" 
                               name="password_confirmation" required 
                               placeholder="Confirmez votre mot de passe">
                        <button type="button" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" id="nextStepBtn" class="login-button">
                        <span class="button-text">Suivant</span>
                        <i class="fas fa-arrow-right button-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Étape 2 -->
            <div id="registerStep2" style="display: none;">
                <div class="form-header">
                    <button type="button" class="back-button" id="backToStep1">
                        <i class="fas fa-arrow-left"></i> Retour
                    </button>
                    <h3>Informations complémentaires</h3>
                </div>

                <div class="form-group">
                    <label for="nationality">Nationalité</label>
                    <div class="input-with-icon">
                        <i class="fas fa-flag"></i>
                        <select id="nationality" name="nationality" class="select-custom @error('nationality') is-invalid @enderror" required>
                            <option value="" disabled selected>Sélectionnez votre nationalité</option>
                            <option value="BI" {{ old('nationality') == 'BI' ? 'selected' : '' }}>Burundi</option>
                            <option value="CD" {{ old('nationality') == 'CD' ? 'selected' : '' }}>RDC</option>
                            <option value="TZ" {{ old('nationality') == 'TZ' ? 'selected' : '' }}>Tanzania</option>
                            <option value="UG" {{ old('nationality') == 'UG' ? 'selected' : '' }}>Ouganda</option>
                            <option value="KE" {{ old('nationality') == 'KE' ? 'selected' : '' }}>Kenya</option>
                            <option value="RW" {{ old('nationality') == 'RW' ? 'selected' : '' }}>Rwanda</option>
                            <option value="other" {{ old('nationality') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                        @error('nationality')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="id_number">Numéro de pièce d'identité</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="id_number" name="id_number" required 
                               value="{{ old('id_number') }}"
                               placeholder="Votre numéro de pièce d'identité"
                               class="@error('id_number') is-invalid @enderror">
                        @error('id_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone_number">Numéro de téléphone</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone_number" name="phone_number" required 
                               value="{{ old('phone_number') }}"
                               placeholder="Votre numéro de téléphone" 
                               pattern="[0-9]{8,15}"
                               class="@error('phone_number') is-invalid @enderror">
                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Adresse</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" id="address" name="address" required 
                               value="{{ old('address') }}"
                               placeholder="Votre adresse complète"
                               class="@error('address') is-invalid @enderror">
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <div class="input-with-icon">
                            <i class="fas fa-city"></i>
                            <input type="text" id="city" name="city" required 
                                   value="{{ old('city') }}"
                                   placeholder="Votre ville"
                                   class="@error('city') is-invalid @enderror">
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="postal_code">Code postal</label>
                        <div class="input-with-icon">
                            <i class="fas fa-mail-bulk"></i>
                            <input type="text" id="postal_code" name="postal_code" required 
                                   value="{{ old('postal_code') }}"
                                   placeholder="Code postal"
                                   class="@error('postal_code') is-invalid @enderror">
                            @error('postal_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" id="terms" name="terms" required
                               {{ old('terms') ? 'checked' : '' }}
                               class="@error('terms') is-invalid @enderror">
                        <span class="checkmark"></span>
                        J'accepte les <a href="#" class="terms-link">conditions d'utilisation</a>
                    </label>
                    @error('terms')
                        <span class="invalid-feedback" role="alert" style="display: block; margin-top: 5px;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="login-button">
                        <span class="button-text">S'inscrire</span>
                        <div class="button-loader">
                            <div class="spinner"></div>
                        </div>
                    </button>
                </div>
            </div>
        </form>

        <div class="login-footer">
            <p>Déjà inscrit ? <a href="{{ route('login') }}">Connectez-vous</a></p>
        </div>
    </div>
</div>

<div class="app-presentation">
    <div class="logo-large animate-logo">
        <i class="fas fa-car-crash"></i>
        <h1 class="app-title">
            <span class="title-text">Assur</span>
            <span class="title-text accent">Auto</span>
        </h1>
        <p class="app-tagline">Votre partenaire de confiance pour une assurance automobile sereine</p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des étapes du formulaire
    const nextStepBtn = document.getElementById('nextStepBtn');
    const backToStep1 = document.getElementById('backToStep1');
    const registerStep1 = document.getElementById('registerStep1');
    const registerStep2 = document.getElementById('registerStep2');
    
    if (nextStepBtn) {
        nextStepBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validation de l'étape 1
            const requiredFields = registerStep1.querySelectorAll('input[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            // Vérification des mots de passe
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            
            if (password.value !== confirmPassword.value) {
                isValid = false;
                password.classList.add('error');
                confirmPassword.classList.add('error');
                showError('Les mots de passe ne correspondent pas');
            } else {
                password.classList.remove('error');
                confirmPassword.classList.remove('error');
            }
            
            // Si valide, passer à l'étape 2
            if (isValid) {
                registerStep1.style.display = 'none';
                registerStep2.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (!document.querySelector('.form-error')) {
                showError('Veuillez remplir tous les champs obligatoires');
            }
        });
    }
    
    // Bouton Retour
    if (backToStep1) {
        backToStep1.addEventListener('click', function(e) {
            e.preventDefault();
            registerStep2.style.display = 'none';
            registerStep1.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Gestion des mots de passe
    document.addEventListener('click', function(e) {
        const toggleBtn = e.target.closest('.toggle-password');
        if (toggleBtn) {
            const input = toggleBtn.previousElementSibling;
            const icon = toggleBtn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    });


    // Fonction pour afficher les erreurs
    function showError(message) {
        let errorDiv = document.querySelector('.form-error');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'form-error error-message';
            document.querySelector('.auth-form').prepend(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
});
</script>
@endpush
@endsection