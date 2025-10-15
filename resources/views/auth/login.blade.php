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
            <h1>Connexion</h1>
            <p class="login-subtitle">Accédez à votre espace de gestion</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
            @csrf
            <div class="form-group">
                <label for="email">Adresse email</label>
                <div class="input-with-icon">
                    <i class="fas fa-envelope"></i>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="votre@email.com">
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
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Votre mot de passe">
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

            <div class="form-options">
                <label class="checkbox">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                    Se souvenir de moi
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-password" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <button type="submit" class="login-button">
                <span class="button-text">Se connecter</span>
                <div class="button-loader">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>

        <div class="login-footer">
            <p>Pas encore de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a></p>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Animation du logo
    const logoLarge = document.querySelector('.logo-large');
    if (logoLarge) {
        setTimeout(() => {
            logoLarge.style.opacity = '1';
            logoLarge.style.transform = 'translateY(0)';
        }, 300);
    }
});
</script>
@endpush