<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AssurAuto')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/loading.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Loading Screen -->
    <div id="loadingScreen" class="loading-screen">
        <div class="loading-content">
            <div class="logo-animated">
                <i class="fas fa-car-crash"></i>
                <span>AssurAuto</span>
            </div>
            <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <div class="logo">
                <i class="fas fa-car-crash"></i>
                <span>AssurAuto</span>
            </div>
            <div class="header-subtitle">Gestion Assurance Automobile</div>
        </div>

        <div class="header-right">
            <div class="user-menu">
                <div class="user-info">
                    <span id="userName">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    @auth
                    <span id="userRole" class="user-role">
                        {{ Auth::user()->is_admin ? 'Admin' : 'Utilisateur' }}
                    </span>
                    @endauth
                </div>
                <div class="user-actions">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Notification System -->
    <div id="notification" class="notification hidden">
        <div class="notification-content">
            <i class="notification-icon"></i>
            <span class="notification-message"></span>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
    // S'assurer que le DOM est complètement chargé
    document.addEventListener('DOMContentLoaded', function() {
        // Cacher l'écran de chargement une fois que tout est chargé
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen) {
            loadingScreen.style.opacity = '0';
            loadingScreen.style.transition = 'opacity 0.5s ease';
            
            // Supprimer l'élément après l'animation
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }
    });
    
    // En cas d'erreur de chargement, masquer quand même l'écran de chargement
    window.addEventListener('load', function() {
        const loadingScreen = document.getElementById('loadingScreen');
        if (loadingScreen && loadingScreen.style.display !== 'none') {
            loadingScreen.style.opacity = '0';
            loadingScreen.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }
    });
    </script>
    @stack('scripts')
</body>
</html>