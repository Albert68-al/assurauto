@extends('layouts.app')

@section('title', 'Journaux d\'Activité')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-history"></i> Journaux d'Activité
        </h1>
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer tous les journaux d\'activité ?')">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Tout supprimer
            </button>
        </form>
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

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres de recherche</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">Rechercher</label>
                    <input type="text" name="search" id="search" class="form-control" placeholder="Description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="action">Action</label>
                    <select name="action" id="action" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', ucfirst($action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from">Date de début</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to">Date de fin</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Liste des activités ({{ $logs->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">ID</th>
                            <th width="15%">Date & Heure</th>
                            <th width="15%">Utilisateur</th>
                            <th width="15%">Action</th>
                            <th width="35%">Description</th>
                            <th width="10%">Adresse IP</th>
                            <th width="5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small><br>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($log->user)
                                    <a href="{{ route('admin.users.show', $log->user) }}">
                                        {{ $log->user->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Utilisateur supprimé</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ 
                                    str_contains($log->action, 'create') ? 'success' : 
                                    (str_contains($log->action, 'update') ? 'info' : 
                                    (str_contains($log->action, 'delete') ? 'danger' : 
                                    (str_contains($log->action, 'login') ? 'primary' : 'secondary'))) 
                                }}">
                                    {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $log->description }}</small>
                                @if($log->properties)
                                    <button type="button" class="btn btn-sm btn-link p-0" data-toggle="modal" data-target="#detailsModal{{ $log->id }}">
                                        <i class="fas fa-info-circle"></i> Détails
                                    </button>

                                    <!-- Modal pour les détails -->
                                    <div class="modal fade" id="detailsModal{{ $log->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails de l'activité #{{ $log->id }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre>{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $log->ip_address }}</small>
                            </td>
                            <td>
                                <form action="{{ route('admin.activity-logs.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce journal ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Aucun journal d'activité trouvé.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de {{ $logs->firstItem() ?? 0 }} à {{ $logs->lastItem() ?? 0 }} sur {{ $logs->total() }} journaux
                </div>
                <div>
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    pre {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        font-size: 12px;
        max-height: 400px;
        overflow-y: auto;
    }
</style>
@endpush
