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
            <div class="flex items-center justify-between h-16 md:h-20">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                </div>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                    <a href="{{ route('courses') }}" class="hover:text-primary-600 transition-colors">Cours</a>
                    <a href="#forfaits" class="hover:text-primary-600 transition-colors">Forfaits</a>
                    <a href="#cours" class="hover:text-primary-600 transition-colors">Cours individuels</a>
                    <a href="{{ route('teacher.register') }}" class="hover:text-primary-600 transition-colors">Enseigner</a>
                </nav>
                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('teacher.login') }}" class="hidden sm:inline-flex text-sm text-gray-500 hover:text-primary-600 transition-colors px-3 py-2">Enseignant</a>
                        <a href="{{ route('login') }}" class="btn-outline text-sm">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm">S'inscrire</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">
                            <div class="w-7 h-7 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-xs font-bold">{{ auth()->user()->initials }}</div>
                            <span class="hidden sm:inline">{{ auth()->user()->full_name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-danger-600 transition-colors">Déconnexion</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero Section --}}
        <section class="relative pt-32 pb-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-50 via-white to-primary-50 overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03]">
                <div class="absolute top-20 left-10 w-72 h-72 bg-primary-500 rounded-full blur-3xl"></div>
                <div class="absolute bottom-10 right-20 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto relative">
                <div class="text-center max-w-4xl mx-auto">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 border border-primary-200 rounded-full text-sm text-primary-700 font-medium mb-8">
                        <span class="w-2 h-2 bg-primary-500 rounded-full animate-pulse"></span>
                        Plateforme éducative nouvelle génération
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-[1.1]">
                        Apprenez avec les
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">meilleurs enseignants</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-gray-500 max-w-3xl mx-auto leading-relaxed">
                        Accédez à des cours de qualité dispensés par des experts. Choisissez parmi nos forfaits complets ou inscrivez-vous à des cours individuels selon vos besoins.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary btn-lg shadow-lg shadow-primary-200/50">
                            Commencer gratuitement
                            <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="#forfaits" class="btn-outline btn-lg">Voir les forfaits</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Bundles --}}
        @if($bundles->count() > 0)
        <section id="forfaits" class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-14">
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-widest">Forfaits complets</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Choisissez votre programme</h2>
                    <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Économisez sur l'inscription à un forfait complet de cours avec nos tarifs préférentiels</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($bundles as $bundle)
                    <div class="group relative bg-white rounded-2xl border border-gray-200 hover:border-primary-200 hover:shadow-xl hover:shadow-primary-100/50 transition-all duration-300 overflow-hidden">
                        @if($bundle->has_bundle_discount && $bundle->bundle_savings_percent > 0)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-danger-500 text-white text-xs font-bold rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/></svg>
                                    Économisez {{ $bundle->bundle_savings_percent }}%
                                </span>
                            </div>
                        @endif
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                <span class="bg-gray-100 px-2 py-1 rounded-md font-medium">{{ $bundle->grade?->level?->name }}</span>
                                <span>{{ $bundle->grade?->name }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $bundle->name }}</h3>
                            @if($bundle->description)
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $bundle->description }}</p>
                            @endif
                            <div class="mt-4 bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center justify-between text-sm mb-3">
                                    <span class="text-gray-600 font-medium">Cours inclus ({{ $bundle->courses->count() }})</span>
                                    <span class="text-xs text-gray-400">avec certificat</span>
                                </div>
                                <ul class="space-y-2">
                                    @foreach($bundle->courses->take(4) as $course)
                                    <li class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-success-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-gray-700">{{ $course->name }}</span>
                                        <span class="text-xs text-gray-400 ml-auto">{{ $course->teacher->full_name ?? '' }}</span>
                                    </li>
                                    @endforeach
                                    @if($bundle->courses->count() > 4)
                                    <li class="text-sm text-primary-600 font-medium pt-1">+ {{ $bundle->courses->count() - 4 }} autres cours</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="mt-5 flex items-end justify-between">
                                <div>
                                    @if($bundle->has_bundle_discount)
                                        <span class="text-sm text-gray-400 line-through">{{ number_format($bundle->total_courses_price, 2) }} DA</span>
                                        <div class="text-2xl font-extrabold text-danger-600">{{ number_format($bundle->bundle_discounted_price, 2) }} DA</div>
                                        <p class="text-xs text-gray-400">au lieu de {{ number_format($bundle->total_courses_price, 2) }} DA</p>
                                    @elseif($bundle->bundle_price)
                                        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($bundle->bundle_price, 2) }} DA</div>
                                        <p class="text-xs text-gray-400">Forfait {{ $bundle->courses->count() }} cours</p>
                                    @else
                                        <div class="text-2xl font-extrabold text-gray-900">{{ number_format($bundle->total_courses_price, 2) }} DA</div>
                                        <p class="text-xs text-gray-400">Prix total des cours</p>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('bundles.details', $bundle->id) }}" class="btn-outline btn-sm">Détails</a>
                                    @auth
                                        @if(auth()->user()->isStudent())
                                        <form method="POST" action="{{ route('courses.enroll-bundle', $bundle) }}">
                                            @csrf
                                            <button type="submit" class="btn-primary btn-sm">S'inscrire</button>
                                        </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('bundles.details', $bundle->id)) }}" class="btn-primary btn-sm">S'inscrire</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('courses') }}" class="btn-primary btn-lg">Explorer tous les programmes</a>
                </div>
            </div>
        </section>
        @endif

        {{-- Individual Courses --}}
        @if($featuredCourses->count() > 0)
        <section id="cours" class="py-24 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-14">
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-widest">Cours individuels</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Apprenez à votre rythme</h2>
                    <p class="mt-4 text-gray-500 text-lg max-w-2xl mx-auto">Inscrivez-vous aux cours de votre choix, indépendamment les uns des autres</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredCourses as $course)
                    <div class="group bg-white rounded-xl border border-gray-200 hover:border-primary-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                        @if($course->thumbnail)
                            <div class="h-40 bg-gray-100 overflow-hidden">
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="h-40 bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                                <svg class="w-12 h-12 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex items-center gap-2 text-xs text-gray-500 mb-2">
                                <span class="bg-gray-100 px-2 py-1 rounded font-medium">{{ $course->section?->grade?->level?->name }}</span>
                                @if($course->section)
                                <span>{{ $course->section->name }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-gray-900">{{ $course->name }}</h3>
                            <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $course->teacher->full_name ?? 'À déterminer' }}</span>
                            </div>
                            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                                @if($course->duration)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $course->duration }}</span>
                                </div>
                                @endif
                                @if($course->max_students)
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $course->remaining_seats }} places</span>
                                </div>
                                @endif
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <div>
                                    @if($course->price)
                                    <span class="text-xl font-bold text-gray-900">{{ number_format($course->price, 2) }} DA</span>
                                    @endif
                                </div>
                                @auth
                                    @if(auth()->user()->isStudent())
                                    <form method="POST" action="{{ route('courses.enroll-course', $course) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary btn-sm">S'inscrire</button>
                                    </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('courses')) }}" class="btn-primary btn-sm">S'inscrire</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Stats Bar --}}
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white border-y border-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-primary-600">5,000+</div>
                        <div class="text-sm text-gray-500 mt-1">Étudiants inscrits</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-primary-600">500+</div>
                        <div class="text-sm text-gray-500 mt-1">Enseignants experts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-primary-600">200+</div>
                        <div class="text-sm text-gray-500 mt-1">Cours disponibles</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl sm:text-4xl font-bold text-primary-600">98%</div>
                        <div class="text-sm text-gray-500 mt-1">Satisfaction</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Why Choose Us --}}
        <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <span class="text-sm font-semibold text-primary-600 uppercase tracking-widest">Pourquoi nous choisir</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Une plateforme conçue pour l'excellence</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="text-center p-8">
                        <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Enseignants qualifiés</h3>
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">Des experts passionnés sélectionnés pour leur excellence pédagogique et leur expérience.</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-14 h-14 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-7 h-7 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Flexibilité totale</h3>
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">Cours individuels ou forfaits complets. Apprenez à votre rythme, selon vos disponibilités.</p>
                    </div>
                    <div class="text-center p-8">
                        <div class="w-14 h-14 bg-warning-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                            <svg class="w-7 h-7 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Suivi personnalisé</h3>
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed">Tableau de bord dédié, suivi des présences, quiz interactifs et rapports détaillés.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Student CTA --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Prêt à commencer votre parcours ?</h2>
                <p class="mt-4 text-primary-100 text-lg max-w-2xl mx-auto">Rejoignez des milliers d'apprenants et donnez un nouvel élan à votre éducation.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3.5 rounded-xl shadow-lg">
                        Créer mon compte
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('courses') }}" class="inline-flex items-center gap-2 btn-lg border-2 border-white text-white hover:bg-white hover:text-primary-600 font-semibold px-8 py-3.5 rounded-xl">
                        Parcourir les cours
                    </a>
                </div>
            </div>
        </section>

        {{-- Teacher CTA --}}
        <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-900 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <span class="text-sm font-semibold text-primary-400 uppercase tracking-widest">Enseignants</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold">Rejoignez notre équipe pédagogique</h2>
                <p class="mt-4 text-gray-300 text-lg max-w-2xl mx-auto">Vous êtes passionné par l'enseignement ? Nous offrons une plateforme moderne, une rémunération attractive et une communauté dynamique.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('teacher.register') }}" class="inline-flex items-center gap-2 btn-lg bg-white text-gray-900 hover:bg-gray-100 font-semibold px-8 py-3.5 rounded-xl shadow-lg">
                        Postuler maintenant
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-900 text-gray-400">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-4 gap-10">
                    <div class="md:col-span-1">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <span class="text-lg font-bold text-white">{{ config('app.name') }}</span>
                        </div>
                        <p class="text-sm leading-relaxed">Plateforme moderne de gestion scolaire et d'apprentissage en ligne.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Plateforme</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="{{ route('courses') }}" class="hover:text-white transition-colors">Cours</a></li>
                            <li><a href="#forfaits" class="hover:text-white transition-colors">Forfaits</a></li>
                            <li><a href="#cours" class="hover:text-white transition-colors">Cours individuels</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Enseignants</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="{{ route('teacher.register') }}" class="hover:text-white transition-colors">Enseigner avec nous</a></li>
                            <li><a href="{{ route('teacher.login') }}" class="hover:text-white transition-colors">Espace enseignant</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Liens utiles</h4>
                        <ul class="space-y-3 text-sm">
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Créer un compte</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Se connecter</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                </div>
            </div>
        </footer>
    </main>
</body>
</html>
