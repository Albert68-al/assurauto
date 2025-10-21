@extends('client.layouts.clientapp')

@section('title', 'Ajouter un véhicule')

@section('content')
<div class="client-section">
    <div class="container">
        <h1>Ajouter un véhicule</h1>

        <form action="{{ route('client.vehicules.store') }}" method="POST" enctype="multipart/form-data" class="vehicule-form">

            @csrf

            <div class="form-group">
                <label for="immatriculation">Immatriculation</label>
                <input type="text" name="immatriculation" value="{{ old('immatriculation') }}">
                @error('immatriculation') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="marque">Marque</label>
                    <input type="text" name="marque" value="{{ old('marque') }}">
                </div>
                <div class="form-group">
                    <label for="modele">Modèle</label>
                    <input type="text" name="modele" value="{{ old('modele') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="annee_vehicule">Année</label>
                    <input type="number" name="annee_vehicule" value="{{ old('annee_vehicule') }}">
                </div>
                <div class="form-group">
                    <label for="usage_vehicule">Usage</label>
                    <input type="text" name="usage_vehicule" value="{{ old('usage_vehicule') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="numero_chassis">Numéro de châssis</label>
                <input type="text" name="numero_chassis" value="{{ old('numero_chassis') }}">
            </div>

            <div class="form-group">
                <label for="vehicule_photo">Photo du véhicule</label>
                <input type="file" name="vehicule_photo" accept="image/*">
            </div>

            <div class="form-actions">
                <a href="{{ route('client.vehicules.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
