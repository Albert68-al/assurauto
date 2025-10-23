@extends('client.layouts.clientapp')

@section('content')
<div class="client-section">
    <div class="container">
        @if($errors->any())
            <div class="alert success">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <h1>Créer une nouvelle police</h1>
        <form action="{{ route('client.polices.store') }}" method="POST" class="vehicule-form">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="vehicule_id" class="form-label">Véhicule</label>
                    <select name="vehicule_id" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ old('vehicule_id') == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="produit_id" class="form-label">Produit</label>
                    <select name="produit_id" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($produits as $produit)
                            <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                {{ $produit->nom }} : {{ $produit->tarif_base }} | {{ $produit->duree }} Mois
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Numéro Police</label>
                <input type="text" name="numero_police" class="form-control" value="{{ old('numero_police') }}" required>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('client.polices.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
