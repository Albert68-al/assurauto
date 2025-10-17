@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier les rôles de {{ $user->name }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Rôles</label>
                            <div class="col-md-6">
                                @foreach($roles as $role)
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->name }}"
                                               {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                               id="role_{{ $role->id }}">
                                        <label for="role_{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection