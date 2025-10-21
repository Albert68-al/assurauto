@extends('client.layouts.clientapp')

@section('title', 'Mes Véhicules')

@section('content')
<div class="vehicule-section">
    <div class="container">
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        <div class="header-actions">
            <a href="{{ route('client.vehicules.create') }}" class="btn btn-primary">+ Ajouter un véhicule</a>
        </div>

        <div class="vehicule-list">
            @forelse($vehicules as $vehicule)
                <div class="vehicule-card">
                    @if($vehicule->vehicule_photo)
                        <img src="{{ asset($vehicule->vehicule_photo) }}" alt="Photo du véhicule" class="vehicule-photo">
                    @else
                        <div class="vehicule-photo placeholder">Aucune image</div>
                    @endif

                    <div class="vehicule-info">
                        <h2>{{ $vehicule->marque }}</h2>
                        <p><strong>Immatriculation :</strong> {{ $vehicule->immatriculation }}</p>
                        <p><strong>Marque : </strong>{{ $vehicule->marque }}</p>
                        <p><strong>Modele : </strong>{{ $vehicule->modele }}</p>
                        <p><strong>Année :</strong> {{ $vehicule->annee_vehicule }}</p>
                        <p><strong>Usage :</strong> {{ $vehicule->usage_vehicule }}</p>
                        <p><strong>Châssis :</strong> {{ $vehicule->numero_chassis }}</p>
                    </div>

                    <div class="vehicule-actions">
                        <a href="{{ route('client.vehicules.edit', $vehicule) }}" class="btn btn-primary">Modifier</a>
                        <form action="{{ route('client.vehicules.destroy', $vehicule) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            @empty
                <h2>Aucun véhicule enregistré.</h2>
            @endforelse
        </div>
    </div>
</div>
@endsection
