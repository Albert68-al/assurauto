@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Vérification d'email

    Bonjour {{ $user->name }},

    Merci de vous être inscrit sur {{ config('app.name') }}. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email.

    @component('mail::button', ['url' => $verificationUrl])
        Vérifier mon email
    @endcomponent

    Si vous n'avez pas créé de compte, aucune autre action n'est requise.

    Merci,<br>
    {{ config('app.name') }}

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
        @endcomponent
    @endslot
@endcomponent