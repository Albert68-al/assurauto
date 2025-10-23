@extends('client.layouts.clientapp')

@section('title', 'Mon Profile')

@section('content')
<div class="account-container">
    <h2 class="account-title">👤 Mon Compte</h2>

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('client.profile.update') }}" method="POST" class="account-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nom complet *</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Adresse email *</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nationality">Nationalité</label>
                <select id="nationality" 
                        name="nationality" 
                        class="select-custom @error('nationality') is-invalid @enderror" 
                        required>
                    <option value="" disabled {{ old('nationality', $user->nationality) ? '' : 'selected' }}>
                        Sélectionnez votre nationalité
                    </option>
                    <option value="BI" {{ old('nationality', $user->nationality) == 'BI' ? 'selected' : '' }}>Burundi</option>
                    <option value="CD" {{ old('nationality', $user->nationality) == 'CD' ? 'selected' : '' }}>RDC</option>
                    <option value="TZ" {{ old('nationality', $user->nationality) == 'TZ' ? 'selected' : '' }}>Tanzanie</option>
                    <option value="UG" {{ old('nationality', $user->nationality) == 'UG' ? 'selected' : '' }}>Ouganda</option>
                    <option value="KE" {{ old('nationality', $user->nationality) == 'KE' ? 'selected' : '' }}>Kenya</option>
                    <option value="RW" {{ old('nationality', $user->nationality) == 'RW' ? 'selected' : '' }}>Rwanda</option>
                    <option value="other" {{ old('nationality', $user->nationality) == 'other' ? 'selected' : '' }}>Autre</option>
                </select>
                @error('nationality')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="id_number">Numéro d'identité</label>
                <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $user->id_number) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="phone_number">Numéro de téléphone</label>
                <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
            </div>

            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="city">Ville</label>
                <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}">
            </div>

            <div class="form-group">
                <label for="postal_code">Code postal</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
