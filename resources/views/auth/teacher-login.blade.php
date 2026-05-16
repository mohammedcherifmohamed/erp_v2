@extends('layouts.auth')

@section('title', 'Espace Enseignant')

@section('content')
<div class="min-h-screen flex">
    {{-- Left: Teacher branding --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-primary-300 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-lg text-center px-12">
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h2 class="text-4xl font-extrabold text-white mb-4">Espace Enseignant</h2>
            <p class="text-lg text-primary-100 leading-relaxed">Accédez à votre tableau de bord, gérez vos cours, suivez vos étudiants et bien plus encore.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <span class="bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-sm text-primary-100">Gestion des cours</span>
                <span class="bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-sm text-primary-100">Présences</span>
                <span class="bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-sm text-primary-100">Notes & Quiz</span>
                <span class="bg-white/10 backdrop-blur rounded-full px-4 py-1.5 text-sm text-primary-100">Annonces</span>
            </div>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 px-6 py-12">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8 lg:hidden">
                <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Espace Enseignant</h2>
                <p class="mt-2 text-sm text-gray-500">Connectez-vous à votre compte enseignant</p>
            </div>

            <div class="hidden lg:block text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Bonjour, enseignant !</h2>
                <p class="mt-2 text-sm text-gray-500">Connectez-vous à votre espace pédagogique</p>
            </div>

            <div class="card p-8">
                <form method="POST" action="{{ route('teacher.login.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="label">Adresse e-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input @error('email') input-error @enderror"
                            placeholder="votre@email.com">
                        @error('email')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="label">Mot de passe</label>
                        <input id="password" type="password" name="password" required
                            class="input @error('password') input-error @enderror"
                            placeholder="Votre mot de passe">
                        @error('password')
                            <p class="text-sm text-danger-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-600">Se souvenir de moi</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Se connecter
                    </button>
                </form>
            </div>

            <div class="mt-6 space-y-3 text-center">
                <p class="text-sm text-gray-500">
                    Pas encore inscrit ?
                    <a href="{{ route('teacher.register') }}" class="text-primary-600 hover:text-primary-500 font-medium">Postuler pour enseigner</a>
                </p>
                <p class="text-xs text-gray-400">
                    Vous avez postulé mais n'avez pas reçu vos identifiants ?
                    <a href="{{ route('teacher.register') }}" class="text-primary-600 hover:text-primary-500">Contactez l'administration</a>
                </p>
                <div class="border-t border-gray-200 pt-3">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        Espace étudiant →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
