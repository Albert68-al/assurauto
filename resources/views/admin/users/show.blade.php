@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user"></i> Profil de {{ $user->name }}
        </h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle"></i> Informations personnelles
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-gray-300"></i>
                    </div>
                    <h5 class="font-weight-bold">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    
                    @if($user->email_verified_at)
                        <span class="badge badge-success badge-lg">
                            <i class="fas fa-check-circle"></i> Compte Actif
                        </span>
                    @else
                        <span class="badge badge-secondary badge-lg">
                            <i class="fas fa-times-circle"></i> Compte Inactif
                        </span>
                    @endif

                    <hr class="my-4">

                    <div class="text-left">
                        <p class="mb-2">
                            <i class="fas fa-phone text-primary"></i>
                            <strong>Téléphone:</strong><br>
                            <span class="ml-4">{{ $user->phone ?? 'Non renseigné' }}</span>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                            <strong>Adresse:</strong><br>
                            <span class="ml-4">{{ $user->address ?? 'Non renseignée' }}</span>
                        </p>
                        @if($user->city || $user->postal_code)
                        <p class="mb-2">
                            <i class="fas fa-city text-primary"></i>
                            <strong>Ville:</strong><br>
                            <span class="ml-4">{{ $user->postal_code }} {{ $user->city }}</span>
                        </p>
                        @endif
                        <p class="mb-2">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <strong>Membre depuis:</strong><br>
                            <span class="ml-4">{{ $user->created_at->format('d/m/Y') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Rôles et Permissions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-shield-alt"></i> Rôles et Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <h6 class="font-weight-bold">Rôles:</h6>
                    <div class="mb-3">
                        @forelse($user->roles as $role)
                            <span class="badge badge-{{ $role->name === 'super_admin' ? 'danger' : ($role->name === 'admin' ? 'warning' : 'info') }} mr-1 mb-1">
                                {{ ucfirst($role->name) }}
                            </span>
                        @empty
                            <p class="text-muted">Aucun rôle assigné</p>
                        @endforelse
                    </div>

                    @if($user->permissions->count() > 0)
                    <hr>
                    <h6 class="font-weight-bold">Permissions directes:</h6>
                    <ul class="list-unstyled">
                        @foreach($user->permissions as $permission)
                            <li><i class="fas fa-check text-success"></i> {{ $permission->name }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Historique des activités -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Historique des activités
                    </h6>
                </div>
                <div class="card-body">
                    @if($activities->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activities as $activity)
                                    <tr>
                                        <td>
                                            <small>{{ $activity->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $activity->action === 'create_user' ? 'success' : 
                                                ($activity->action === 'update_user' ? 'info' : 
                                                ($activity->action === 'delete_user' ? 'danger' : 'secondary')) 
                                            }}">
                                                {{ str_replace('_', ' ', ucfirst($activity->action)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $activity->description }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $activity->ip_address }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $activities->links() }}
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Aucune activité enregistrée pour cet utilisateur.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Connexions
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $activities->where('action', 'login')->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Modifications
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $activities->where('action', 'update_user')->count() }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-edit fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Dernière activité
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                        @if($activities->first())
                                            {{ $activities->first()->created_at->diffForHumans() }}
                                        @else
                                            Jamais
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush