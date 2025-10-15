<aside class="sidebar">
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-module="dashboard">
            <i class="fas fa-chart-line"></i>
            <span>Tableau de bord</span>
        </a>
        <a href="{{ route('clients.index') }}" class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}" data-module="clients">
            <i class="fas fa-users"></i>
            <span>Clients</span>
        </a>
        <a href="{{ route('polices.index') }}" class="nav-item" data-module="polices">
            <i class="fas fa-file-contract"></i>
            <span>Polices</span>
        </a>
        <a href="{{ route('sinistres.index') }}" class="nav-item" data-module="sinistres">
            <i class="fas fa-car-crash"></i>
            <span>Sinistres</span>
        </a>
        <a href="{{ route('paiements.index') }}" class="nav-item" data-module="paiements">
            <i class="fas fa-credit-card"></i>
            <span>Paiements</span>
        </a>
        <a href="{{ route('comesa.index') }}" class="nav-item" data-module="comesa">
            <i class="fas fa-globe-africa"></i>
            <span>Module COMESA</span>
        </a>
        @can('manage users')
        <a href="{{ route('users.index') }}" class="nav-item" data-module="utilisateurs">
            <i class="fas fa-user-shield"></i>
            <span>Utilisateurs</span>
        </a>
        @endcan
        @can('manage settings')
        <a href="{{ route('settings') }}" class="nav-item" data-module="configuration">
            <i class="fas fa-cogs"></i>
            <span>Configuration</span>
        </a>
        @endcan
    </nav>
</aside>