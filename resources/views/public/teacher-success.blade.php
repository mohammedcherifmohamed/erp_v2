<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }} | Candidature envoyée</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-lg w-full">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">SchoolERP</a>
        </div>

        <div class="card p-10 text-center">
            <div class="w-20 h-20 bg-success-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Candidature envoyée !</h1>
            <p class="text-gray-500 mb-6">Merci <strong>{{ session('teacher_name') }}</strong>, votre candidature a bien été reçue.</p>

            <div class="bg-gray-50 rounded-lg p-6 mb-6 text-left space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Nom</span>
                    <span class="text-sm font-medium">{{ session('teacher_name') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-sm font-medium">{{ session('teacher_email') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Spécialisation</span>
                    <span class="text-sm font-medium">{{ session('teacher_specialization') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Statut</span>
                    <span class="badge-warning">En attente d'approbation</span>
                </div>
            </div>

            <div class="bg-primary-50 rounded-lg p-4 mb-8 text-sm text-primary-800 text-left">
                <p class="font-medium mb-1">Prochaines étapes :</p>
                <ul class="space-y-1 text-primary-700">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        Notre équipe pédagogique examine votre candidature
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        Vous serez contacté sous 48 heures
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        Une fois approuvé, connectez-vous à votre <a href="{{ route('teacher.login') }}" class="text-primary-700 font-medium underline">espace enseignant</a>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="btn-primary">Retour à l'accueil</a>
                <a href="{{ route('courses') }}" class="btn-outline">Voir les cours</a>
            </div>
        </div>
    </div>
</body>
</html>
