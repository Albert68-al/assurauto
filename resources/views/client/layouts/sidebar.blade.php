<aside class="sidebar">
    <nav class="sidebar-nav">
        <a href="{{ route('client.dashboard.index') }}" class="nav-item {{ request()->routeIs('client.dashboard.*') ? 'active' : '' }}" data-module="dashboard">
            <i class="fas fa-chart-line"></i>
            <span>Tableau de bord</span>
        </a>
        <a href="{{ route('client.vehicules.index') }}" class="nav-item {{ request()->routeIs('client.vehicules.*') ? 'active' : '' }}" data-module="vehicules">
            <i class="fas fa-users"></i>
            <span>Vehicules</span>
        </a>
        <a href="{{ route('client.polices.index') }}" class="nav-item {{ request()->routeIs('client.polices.*') ? 'active' : '' }}" data-module="polices">
            <i class="fas fa-file-contract"></i>
            <span>Assurances</span>
        </a>
        <a href="{{ route('client.sinistre.index') }}" class="nav-item {{ request()->routeIs('sinistre.*') ? 'active' : '' }}" data-module="sinistres">
            <i class="fas fa-file-contract"></i>
            <span>Sinistres</span>
        </a>
        <a href="{{ route('client.wallet.index') }}" class="nav-item {{ request()->routeIs('wallet.*') ? 'active' : '' }}" data-module="wallets">
            <i class="fas fa-car-crash"></i>
            <span>Portefeuille</span>
        </a>
        <a href="{{ route('client.profile.index') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}" data-module="profiles">
            <i class="fas fa-credit-card"></i>
            <span>Profile</span>
        </a>
        
    </nav>
</aside>