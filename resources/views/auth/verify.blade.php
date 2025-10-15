@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo animate-logo">
                <i class="fas fa-car-crash"></i>
                <span class="logo-text">Assur</span><span class="logo-text accent">Auto</span>
            </div>
            <h1>Vérification d'email</h1>
        </div>

        <div class="verification-message">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    Un nouveau lien de vérification a été envoyé à votre adresse email.
                </div>
            @endif

            <p>Avant de continuer, veuillez vérifier votre email pour un lien de vérification.</p>
            <p>Si vous n'avez pas reçu l'email,</p>
            
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                    cliquez ici pour en demander un autre
                </button>.
            </form>
        </div>
    </div>
</div>
@endsection