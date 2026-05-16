<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
    {{-- Navbar --}}
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">SchoolERP</span>
                </div>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
                    <a href="#features" class="hover:text-primary-600 transition-colors">Fonctionnalités</a>
                    <a href="{{ route('courses') }}" class="hover:text-primary-600 transition-colors">Cours</a>
                    <a href="{{ route('teacher.register') }}" class="hover:text-primary-600 transition-colors">Enseigner</a>
                </nav>
                <div class="flex items-center gap-4">
                    @guest
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="{{ route('teacher.login') }}" class="text-sm text-gray-500 hover:text-primary-600 transition-colors px-3 py-2">Enseignant</a>
                        </div>
                        <a href="{{ route('login') }}" class="btn-outline">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary">Commencer</a>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                                {{ auth()->user()->full_name }}
                            </a>
                            <div class="w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">
                                {{ auth()->user()->initials }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-gray-500 hover:text-danger-600 transition-colors">Déconnexion</button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero Section --}}
        <section class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-50 via-white to-primary-100 overflow-hidden">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute top-20 left-10 w-72 h-72 bg-primary-400 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-20 w-96 h-96 bg-primary-300 rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto relative">
                <div class="text-center max-w-4xl mx-auto">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        Plateforme de gestion
                        <span class="text-primary-600">scolaire moderne</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-gray-500 max-w-3xl mx-auto leading-relaxed">
                        Un système ERP + LMS complet pour les établissements éducatifs. Gérez les étudiants, enseignants, classes, présences, paiements et plus — le tout en un seul endroit.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary btn-lg shadow-lg shadow-primary-200">Essai gratuit</a>
                        <a href="#features" class="btn-outline btn-lg">En savoir plus</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Stats Bar --}}
        <section class="py-12 px-4 sm:px-6 lg:px-8 bg-white border-y border-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-4">
                        <div class="text-3xl font-bold text-primary-600">5,000+</div>
                        <div class="text-sm text-gray-500 mt-1">Étudiants</div>
                    </div>
                    <div class="text-center p-4">
                        <div class="text-3xl font-bold text-primary-600">500+</div>
                        <div class="text-sm text-gray-500 mt-1">Enseignants</div>
                    </div>
                    <div class="text-center p-4">
                        <div class="text-3xl font-bold text-primary-600">200+</div>
                        <div class="text-sm text-gray-500 mt-1">Classes</div>
                    </div>
                    <div class="text-center p-4">
                        <div class="text-3xl font-bold text-primary-600">50+</div>
                        <div class="text-sm text-gray-500 mt-1">Établissements</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="py-20 bg-gray-50 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Tout ce dont vous avez besoin pour gérer votre école</h2>
                    <p class="mt-4 text-gray-500 text-lg">Solution ERP + LMS complète pour les établissements éducatifs modernes</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Gestion académique</h3>
                        <p class="mt-2 text-sm text-gray-500">Gérez facilement les niveaux, classes, cours et emplois du temps.</p>
                    </div>
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Portail étudiant & enseignant</h3>
                        <p class="mt-2 text-sm text-gray-500">Tableaux de bord dédiés pour les étudiants, enseignants et parents.</p>
                    </div>
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h3v6m6-6a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Paiements & facturation</h3>
                        <p class="mt-2 text-sm text-gray-500">Suivez les paiements, générez des factures et gérez les finances.</p>
                    </div>
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Suivi des présences</h3>
                        <p class="mt-2 text-sm text-gray-500">Marquez et suivez les présences avec des analyses détaillées.</p>
                    </div>
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Système de quiz</h3>
                        <p class="mt-2 text-sm text-gray-500">Créez des quiz avec correction automatique pour les questions à choix multiples.</p>
                    </div>
                    <div class="card p-6 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Annonces</h3>
                        <p class="mt-2 text-sm text-gray-500">Tenez tout le monde informé avec des annonces ciblées.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA Section: Start Learning Today --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Commencez à apprendre dès aujourd'hui</h2>
                <p class="mt-4 text-primary-100 text-lg max-w-2xl mx-auto">Rejoignez des milliers d'étudiants qui apprennent déjà avec nous. Accédez à une éducation de qualité dispensée par des enseignants experts.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl shadow-lg">S'inscrire maintenant</a>
                    <a href="{{ route('courses') }}" class="btn-lg border-2 border-white text-white hover:bg-white hover:text-primary-600 font-semibold px-8 py-3 rounded-xl">Parcourir les cours</a>
                </div>
            </div>
        </section>

        {{-- Courses Preview Section --}}
        <section id="courses" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Classes en vedette</h2>
                    <p class="mt-4 text-gray-500 text-lg">Découvrez nos classes les plus populaires et commencez à apprendre dès aujourd'hui</p>
                </div>
                @if(isset($publicClasses) && $publicClasses->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($publicClasses->take(6) as $class)
                            <div class="card overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                                                <span class="badge-gray">{{ $class->grade?->level?->name }}</span>
                                                <span>{{ $class->grade?->name }}</span>
                                            </div>
                                            <h3 class="font-bold text-gray-900 text-xl">{{ $class->name }}</h3>
                                        </div>
                                        @if($class->remaining_seats <= 0)
                                            <span class="badge-danger flex-shrink-0">Complet</span>
                                        @elseif($class->remaining_seats <= 5)
                                            <span class="badge-warning flex-shrink-0">Plus que {{ $class->remaining_seats }} places</span>
                                        @else
                                            <span class="badge-success flex-shrink-0">{{ $class->remaining_seats }} places</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">{{ $class->description ?? 'Aucune description' }}</p>
                                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Cours inclus</p>
                                        <ul class="space-y-1">
                                            @foreach($class->courses->take(3) as $course)
                                                <li class="text-sm text-gray-700 flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-success-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    {{ $course->name }}
                                                    @if($course->price)
                                                        <span class="text-xs text-gray-400 ml-auto">{{ number_format($course->price, 2) }} DA</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                            @if($class->courses->count() > 3)
                                                <li class="text-sm text-primary-600 font-medium">+ {{ $class->courses->count() - 3 }} autres cours</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            @if($class->has_reduction)
                                                <span class="text-sm text-gray-400 line-through">{{ number_format($class->total_courses_price, 2) }} DA</span>
                                                <span class="text-2xl font-bold text-danger-600 ml-2">{{ number_format($class->reduction_price, 2) }} DA</span>
                                            @else
                                                <span class="text-2xl font-bold text-gray-900">{{ number_format($class->total_courses_price, 2) }} DA</span>
                                            @endif
                                            <p class="text-xs text-gray-400">Forfait {{ $class->courses->count() }} cours</p>
                                        </div>
                                        <a href="{{ route('courses.details', $class->id) }}" class="btn-primary btn-sm">Détails</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-10">
                        <a href="{{ route('courses') }}" class="btn-primary btn-lg">Voir tous les cours</a>
                    </div>
                @else
                    <div class="text-center py-16 text-gray-500 bg-white rounded-2xl">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <p class="text-lg font-medium">Aucune classe disponible pour le moment</p>
                        <p class="text-sm mt-1">Revenez bientôt pour découvrir de nouvelles offres de cours.</p>
                    </div>
                @endif
            </div>
        </section>

        {{-- CTA Section: Join Our Teaching Team --}}
        <section id="teachers" class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-gray-900 to-gray-800 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Rejoignez notre équipe pédagogique</h2>
                <p class="mt-4 text-gray-300 text-lg max-w-2xl mx-auto">Passionné par l'éducation ? Nous recherchons des enseignants talentueux pour rejoindre notre communauté grandissante d'éducateurs.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('teacher.register') }}" class="btn-lg bg-white text-gray-900 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl shadow-lg">Postulez maintenant</a>
                    <a href="#benefits" class="btn-lg border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold px-8 py-3 rounded-xl">Voir les avantages</a>
                </div>
            </div>
        </section>

        {{-- Teacher Registration Section --}}
        <section id="benefits" class="py-20 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Pourquoi enseigner avec SchoolERP ?</h2>
                    <p class="mt-4 text-gray-500 text-lg">Nous fournissons tout ce dont vous avez besoin pour offrir une éducation exceptionnelle</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Horaires flexibles</h3>
                        <p class="mt-2 text-sm text-gray-500">Enseignez selon votre propre emploi du temps. Nous nous adaptons à vos disponibilités.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Rémunération attractive</h3>
                        <p class="mt-2 text-sm text-gray-500">Rémunération compétitive avec opportunités d'évolution et primes.</p>
                    </div>
                    <div class="text-center p-6">
                        <div class="w-14 h-14 bg-warning-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Outils modernes</h3>
                        <p class="mt-2 text-sm text-gray-500">Accédez à notre plateforme LMS de pointe avec des outils pédagogiques puissants.</p>
                    </div>
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('teacher.register') }}" class="btn-primary btn-lg">Postulez maintenant</a>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-900 text-gray-400">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <span class="text-lg font-bold text-white">SchoolERP</span>
                        </div>
                        <p class="text-sm leading-relaxed">Plateforme moderne de gestion scolaire pour les établissements éducatifs. Autonomiser les enseignants, les étudiants et les parents.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Plateforme</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#features" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                            <li><a href="{{ route('courses') }}" class="hover:text-white transition-colors">Cours</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Tarifs</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Enseignants</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('teacher.register') }}" class="hover:text-white transition-colors">Enseigner avec nous</a></li>
                            <li><a href="#benefits" class="hover:text-white transition-colors">Avantages</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4">Société</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">À propos</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10 pt-8 border-t border-gray-800 text-center text-sm">
                    &copy; {{ date('Y') }} SchoolERP. Tous droits réservés.
                </div>
            </div>
        </footer>
    </main>
</body>
</html>
