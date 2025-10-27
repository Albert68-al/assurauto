<aside class="sidebar">
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-module="dashboard">
            <i class="fas fa-chart-line"></i>
            <span>Tableau de bord</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('client.dashboard.*') ? 'active' : '' }}" data-module="clients">
            <i class="fas fa-users"></i>
            <span>Clients</span>
        </a>
        <a href="{{ route('produits.index') }}" class="nav-item {{ request()->routeIs('produits.*') ? 'active' : '' }}" data-module="produits">
            <i class="fas fa-file-contract"></i>
            <span>Produits</span>
        </a>
        <a href="{{ route('admin.polices.index') }}" class="nav-item {{ request()->routeIs('admin.polices.*') ? 'active' : '' }}" data-module="polices">
            <i class="fas fa-file-contract"></i>
            <span>Polices</span>
        </a>
        <a href="{{ route('client.sinistre.index') }}" class="nav-item {{ request()->routeIs('sinistres.*') ? 'active' : '' }}" data-module="sinistres">
            <i class="fas fa-car-crash"></i>
            <span>Sinistres</span>
        </a>
        <a href="{{ route('wallets.index') }}" class="nav-item {{ request()->routeIs('wallets.*') ? 'active' : '' }}" data-module="paiements">
            <i class="fas fa-credit-card"></i>
            <span>Paiements</span>
        </a>
        <a href="{{ route('comesa.index') }}" class="nav-item {{ request()->routeIs('comesa*') ? 'active' : '' }}" data-module="comesa">
            <i class="fas fa-globe-africa"></i>
            <span>Module COMESA</span>
        </a>
        
        @auth
        @if(auth()->user()->hasRole(['super_admin', 'admin']))
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}" data-module="utilisateurs">
            <i class="fas fa-user-shield"></i>
            <span>Utilisateurs</span>
        </a>
        
        <div class="nav-item has-submenu {{ request()->is('admin/settings*', 'admin/roles*', 'admin/permissions*', 'admin/backups*', 'admin/activity-logs*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="nav-link" onclick="toggleSubmenu(this)">
                <i class="fas fa-cogs"></i>
                <span>Configuration Système</span>
                <i class="fas fa-chevron-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>Paramètres Généraux</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.roles.index') }}" class="submenu-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-tag"></i>
                        <span>Rôles & Permissions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.backups.index') }}" class="submenu-link {{ request()->routeIs('admin.backups*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i>
                        <span>Sauvegardes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.activity-logs.index') }}" class="submenu-link {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Journaux d'Activité</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif
        @endauth
    </nav>
</aside>