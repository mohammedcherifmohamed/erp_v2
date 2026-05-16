<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }} | {{ $classe->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <header class="absolute top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <a href="{{ url('/') }}" class="text-xl font-bold text-white">SchoolERP</a>
                </div>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Accueil</a>
                    <a href="{{ route('courses') }}" class="hover:text-white transition-colors">Cours</a>
                </nav>
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="btn-outline border-white text-white hover:bg-white hover:text-primary-600">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary">S'inscrire</a>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-white hover:text-primary-200 transition-colors">
                                {{ auth()->user()->full_name }}
                            </a>
                            <div class="w-8 h-8 bg-white/20 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                {{ auth()->user()->initials }}
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-primary-200 hover:text-white transition-colors">Déconnexion</button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section class="pt-32 pb-20 bg-gradient-to-br from-primary-600 to-primary-900 text-white px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 text-sm text-primary-200 mb-4">
                            <a href="{{ route('courses') }}" class="hover:text-white transition-colors">&larr; Retour aux cours</a>
                            <span class="w-1 h-1 bg-primary-400 rounded-full"></span>
                            <span>{{ $classe->grade->level->name ?? 'N/A' }}</span>
                            <span class="w-1 h-1 bg-primary-400 rounded-full"></span>
                            <span>{{ $classe->grade->name ?? 'N/A' }}</span>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-extrabold">{{ $classe->name }}</h1>
                        <p class="mt-4 text-primary-100 text-lg max-w-2xl">{{ $classe->description }}</p>
                        <div class="mt-6 flex flex-wrap items-center gap-6 text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>{{ $classe->courses->count() }} cours</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $classe->homeroomTeacher->full_name ?? 'À déterminer' }}</span>
                            </div>
                            @if($classe->remaining_seats > 0)
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="badge-success">{{ $classe->remaining_seats }} places restantes</span>
                                </div>
                            @else
                                <span class="badge-danger">Classe complète</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-center lg:text-right">
                        @if($classe->has_reduction)
                            <div class="text-sm text-primary-200 line-through">{{ number_format($classe->total_courses_price, 2) }} DA</div>
                            <div class="text-5xl font-extrabold">{{ number_format($classe->reduction_price, 2) }} DA</div>
                        @else
                            <div class="text-5xl font-extrabold">{{ number_format($classe->total_courses_price, 2) }} DA</div>
                        @endif
                        <p class="text-primary-200 mt-1">{{ $classe->courses->count() }} cours inclus</p>
                        @if($classe->remaining_seats > 0)
                            @auth
                                @if(auth()->user()->isStudent())
                                    <form method="POST" action="{{ route('courses.enroll', $classe) }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="btn-primary btn-lg">S'inscrire au forfait complet</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="btn-primary btn-lg mt-4 inline-block">Connectez-vous pour vous inscrire</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Course Details --}}
        <section class="py-16 px-4">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-10">
                    <div class="card p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">À propos de cette classe</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $classe->description ?? 'Aucune description disponible.' }}</p>
                    </div>

                    <div class="card p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Objectifs</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $classe->objectives ?? 'Aucun objectif spécifié.' }}</p>
                    </div>

                    <div class="card p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Cours inclus</h2>
                        <div class="divide-y divide-gray-100">
                            @forelse($classe->courses as $course)
                                <div class="py-4 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $course->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ $course->teacher->full_name ?? 'À déterminer' }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if($course->price)
                                            <div class="text-right">
                                                <span class="text-lg font-bold text-gray-900">{{ number_format($course->price, 2) }} DA</span>
                                                <p class="text-xs text-gray-400">Ce cours seulement</p>
                                            </div>
                                        @endif
                                        @auth
                                            @if(auth()->user()->isStudent())
                                                <form method="POST" action="{{ route('courses.enroll-course', $course) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-primary btn-sm">S'inscrire</button>
                                                </form>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="btn-outline btn-sm">Connectez-vous</a>
                                        @endauth
                                    </div>
                                </div>
                            @empty
                                <p class="py-4 text-gray-500">Aucun cours attribué pour le moment.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Schedule --}}
                    <div class="card p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Emploi du temps</h2>
                        @if($classe->schedules->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200">
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Jour</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Heure</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Cours</th>
                                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Enseignant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($classe->schedules as $schedule)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                <td class="py-3 px-4 text-gray-700 font-medium">{{ $schedule->day_of_week ?? 'N/A' }}</td>
                                                <td class="py-3 px-4 text-gray-600">
                                                    {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }} - {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}
                                                </td>
                                                <td class="py-3 px-4 text-gray-600">{{ $schedule->course->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $schedule->course->teacher->full_name ?? 'TBD' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">Aucun emploi du temps disponible pour le moment.</p>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="card p-6 text-center">
                        <div class="w-20 h-20 bg-primary-100 rounded-full mx-auto flex items-center justify-center">
                            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h3 class="mt-4 font-semibold text-gray-900">{{ $classe->homeroomTeacher->full_name ?? 'À déterminer' }}</h3>
                        <p class="text-sm text-gray-500">Professeur principal</p>
                        @if($classe->homeroomTeacher && $classe->homeroomTeacher->teacherProfile)
                            <p class="mt-3 text-sm text-gray-600">{{ Str::limit($classe->homeroomTeacher->teacherProfile->bio ?? 'Aucune biographie disponible.', 120) }}</p>
                        @endif
                    </div>

                    <div class="card p-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Informations rapides</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Niveau</span>
                                <span class="font-medium text-gray-700">{{ $classe->grade->level->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Classe</span>
                                <span class="font-medium text-gray-700">{{ $classe->grade->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Cours</span>
                                <span class="font-medium text-gray-700">{{ $classe->courses_count }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Étudiants</span>
                                <span class="font-medium text-gray-700">{{ $classe->enrollments->count() ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Capacité</span>
                                <span class="font-medium text-gray-700">{{ $classe->capacity ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Enrollment CTA --}}
        @if($classe->remaining_seats > 0)
            <section class="py-16 bg-gradient-to-r from-primary-600 to-primary-800 text-white px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl font-bold">Prêt à rejoindre cette classe ?</h2>
                    <p class="mt-3 text-primary-100 text-lg">Réservez votre place dès aujourd'hui. Seulement {{ $classe->remaining_seats }} places restantes !</p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    @if(auth()->user()->isStudent())
                        <form method="POST" action="{{ route('courses.enroll', $classe) }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl">S'inscrire maintenant</button>
                        </form>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl">Aller au tableau de bord</a>
                    @endif
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl">Connectez-vous pour vous inscrire</a>
                @endauth
                <a href="{{ route('home') }}" class="btn-lg border-2 border-white text-white hover:bg-white hover:text-primary-600 font-semibold px-8 py-3 rounded-xl">Retour à l'accueil</a>
                    </div>
                </div>
            </section>
        @endif
    </main>

    <footer class="py-8 bg-gray-900 text-gray-400 text-center text-sm">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} SchoolERP. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
