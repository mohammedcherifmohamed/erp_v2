@extends('layouts.auth')

@section('title', 'Créer un compte')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8 py-12">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Créer votre compte</h2>
            <p class="mt-2 text-sm text-gray-500">Rejoignez SchoolERP en tant qu'étudiant</p>
        </div>

        <div class="card p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">Prénom</label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="input @error('first_name') input-error @enderror"
                            placeholder="Jean">
                        @error('first_name')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Nom</label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="input @error('last_name') input-error @enderror"
                            placeholder="Dupont">
                        @error('last_name')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="label">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="input @error('email') input-error @enderror"
                        placeholder="vous@exemple.com">
                    @error('email')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="label">Téléphone (optionnel)</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                        class="input" placeholder="+212 6 00 00 00 00">
                </div>

                <div>
                    <label for="password" class="label">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                        class="input @error('password') input-error @enderror"
                        placeholder="Minimum 8 caractères">
                    @error('password')
                        <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="label">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="input" placeholder="Répétez votre mot de passe">
                </div>

                <button type="submit" class="btn-primary w-full">
                    Créer le compte
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-gray-500 mt-6">
            Vous avez déjà un compte ?
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-500 font-medium">Se connecter</a>
        </p>

        <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-500">Vous êtes enseignant ?</p>
            <div class="flex items-center justify-center gap-3 text-sm mt-1">
                <a href="{{ route('teacher.login') }}" class="text-primary-600 hover:text-primary-500 font-medium">Connexion enseignant</a>
                <span class="text-gray-300">•</span>
                <a href="{{ route('teacher.register') }}" class="text-primary-600 hover:text-primary-500 font-medium">Postuler</a>
            </div>
        </div>
    </div>
</div>
@endsection