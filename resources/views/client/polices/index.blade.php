@extends('client.layouts.clientapp')

@section('content')
<div class="client-section"> 
    <div class="container">
        <div class="header-actions">
            <a href="{{ route('client.polices.create') }}" class="btn btn-primary">+ Demander une assurance</a>
        </div>
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Véhicule</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Montant</th>
                    <th>Garanties</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($polices as $police)
                <tr>
                    <td>{{ $police->numero_police }}</td>
                    <td>{{ $police->vehicule->immatriculation ?? 'N/A' }}</td>
                    <td>{{ $police->date_debut }}</td>
                    <td>{{ $police->date_fin }}</td>
                    <td>{{ $police->montant_prime }}</td>
                    <td>{{ $police->garanties }}</td>
                    <td>{{ $police->statut }}</td>
                    <td>
                        @if($police->statut === 'En attente')
                            <a href="{{ route('client.polices.edit', $police) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('client.polices.destroy', $police) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette police ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
<div>
@endsection
