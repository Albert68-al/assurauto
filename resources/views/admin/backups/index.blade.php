@extends('layouts.app')

@section('title', 'Gestion des Sauvegardes')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-database"></i> Gestion des Sauvegardes
        </h1>
        <form action="{{ route('admin.backups.create') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="return confirm('Créer une nouvelle sauvegarde ?')">
                <i class="fas fa-plus"></i> Créer une sauvegarde
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Informations -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nombre de sauvegardes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($backups) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-gray-300"></i>
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
                                Espace utilisé
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(array_sum(array_column($backups, 'size')) / 1024 / 1024, 2) }} MB
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300"></i>
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
                                Dernière sauvegarde
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                @if(count($backups) > 0)
                                    {{ \Carbon\Carbon::parse($backups[0]['date'])->diffForHumans() }}
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

    <!-- Liste des sauvegardes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des sauvegardes</h6>
        </div>
        <div class="card-body">
            @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="40%">Nom du fichier</th>
                                <th width="15%">Taille</th>
                                <th width="20%">Date de création</th>
                                <th width="25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td>
                                    <i class="fas fa-file-archive text-primary"></i>
                                    {{ $backup['name'] }}
                                </td>
                                <td>
                                    {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($backup['date'])->format('d/m/Y H:i:s') }}
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($backup['date'])->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('admin.backups.download', $backup['name']) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Télécharger">
                                        <i class="fas fa-download"></i> Télécharger
                                    </a>
                                    
                                    <form action="{{ route('admin.backups.restore', $backup['name']) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('⚠️ ATTENTION : La restauration écrasera toutes les données actuelles. Êtes-vous sûr de vouloir continuer ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning" title="Restaurer">
                                            <i class="fas fa-undo"></i> Restaurer
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.backups.destroy', $backup['name']) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Supprimer cette sauvegarde ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-4x mb-3"></i>
                    <p class="h5">Aucune sauvegarde disponible</p>
                    <p>Cliquez sur "Créer une sauvegarde" pour commencer.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Informations importantes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-exclamation-triangle"></i> Informations importantes
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold">
                        <i class="fas fa-info-circle text-info"></i> À propos des sauvegardes
                    </h6>
                    <ul class="small">
                        <li>Les sauvegardes incluent la base de données et les fichiers importants</li>
                        <li>Les sauvegardes sont stockées dans <code>storage/app/backups</code></li>
                        <li>Il est recommandé de télécharger les sauvegardes régulièrement</li>
                        <li>Les anciennes sauvegardes sont automatiquement supprimées selon la configuration</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold">
                        <i class="fas fa-shield-alt text-danger"></i> Sécurité
                    </h6>
                    <ul class="small">
                        <li>⚠️ La restauration écrasera toutes les données actuelles</li>
                        <li>⚠️ Assurez-vous de créer une sauvegarde avant toute restauration</li>
                        <li>⚠️ Stockez vos sauvegardes dans un endroit sûr</li>
                        <li>⚠️ Ne partagez jamais vos fichiers de sauvegarde</li>
                    </ul>
                </div>
            </div>

            <hr>

            <div class="alert alert-info mb-0">
                <i class="fas fa-cog"></i>
                <strong>Configuration :</strong>
                Pour modifier les paramètres de sauvegarde automatique, rendez-vous sur la 
                <a href="{{ route('admin.settings.index') }}" class="alert-link">page de configuration</a>.
            </div>
        </div>
    </div>
</div>
@endsection
