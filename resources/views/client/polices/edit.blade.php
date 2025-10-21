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
        <h1>Modifier la police</h1>
        <form action="{{ route('client.polices.update', $police) }}" method="POST" class="vehicule-form">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label for="vehicule_id" class="form-label">Véhicule</label>
                    <select name="vehicule_id" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" {{ old('vehicule_id', $police->vehicule_id) == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Numéro Police</label>
                    <input type="text" name="numero_police" class="form-control" value="{{ old('numero_police', $police->numero_police ) }}" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date Début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', $police->date_debut) }}" required>
                </div>
                <div class="form-group">
                    <label>Date Fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', $police->date_fin) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Montant Prime</label>
                <input type="number" step="0.01" name="montant_prime" class="form-control" value="{{ old('montant_prime', $police->montant_prime) }}" required>
            </div>

            <div class="form-group">
                <label>Garanties</label>
                <textarea name="garanties" class="form-control">{{ old('garanties', $police->garanties) }}</textarea>
            </div>
            <div class="form-actions">
                <a href="{{ route('client.polices.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
