@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails de l'utilisateur</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-4 text-md-right">Nom :</label>
                        <div class="col-md-6">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 text-md-right">Email :</label>
                        <div class="col-md-6">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 text-md-right">Rôles :</label>
                        <div class="col-md-6">
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier les rôles
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection