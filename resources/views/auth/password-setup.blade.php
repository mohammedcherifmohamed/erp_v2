@extends('layouts.app')

@section('title', 'Configurer mon mot de passe')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="card max-w-md w-full">
        <div class="card-body">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Configurer mon mot de passe</h1>
                <p class="mt-2 text-sm text-gray-600">
                    Définissez votre mot de passe pour activer votre compte.
                </p>
            </div>

            <form method="POST" action="{{ route('password.setup.store', $tokenModel->token) }}" class="space-y-6">
                @csrf

                <div>
                    <label for="password" class="label">Nouveau mot de passe <span class="text-danger-500">*</span></label>
                    <input id="password" type="password" name="password" required
                           class="input @error('password') input-error @enderror"
                           placeholder="Minimum 8 caractères">
                    @error('password') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirmer le mot de passe <span class="text-danger-500">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="input @error('password_confirmation') input-error @enderror"
                           placeholder="Confirmer le mot de passe">
                    @error('password_confirmation') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary w-full">
                    Enregistrer le mot de passe
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
