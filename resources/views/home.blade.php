@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tableau de bord</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>Bienvenue, {{ Auth::user()->name }} !</p>
                    <p>Vous êtes connecté avec l'adresse : {{ Auth::user()->email }}</p>
                    
                    <div class="user-info">
                        <p><strong>Nationalité :</strong> {{ Auth::user()->nationality }}</p>
                        <p><strong>Numéro de pièce :</strong> {{ Auth::user()->id_number }}</p>
                        <p><strong>Téléphone :</strong> {{ Auth::user()->phone_number }}</p>
                        <p><strong>Adresse :</strong> {{ Auth::user()->address }}</p>
                        <p><strong>Ville :</strong> {{ Auth::user()->city }}</p>
                        <p><strong>Code postal :</strong> {{ Auth::user()->postal_code }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            Se déconnecter
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection