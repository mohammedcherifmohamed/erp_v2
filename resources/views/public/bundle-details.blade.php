<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | {{ $classe->name }}</title>
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
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <a href="{{ url('/') }}" class="text-xl font-bold text-white">{{ config('app.name') }}</a>
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
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-white hover:text-primary-200 transition-colors">{{ auth()->user()->full_name }}</a>
                            <div class="w-8 h-8 bg-white/20 text-white rounded-full flex items-center justify-center text-sm font-bold">{{ auth()->user()->initials }}</div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section class="pt-32 pb-20 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white px-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-400 rounded-full blur-3xl"></div>
            </div>
            <div class="max-w-7xl mx-auto relative">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 text-sm text-primary-200 mb-4">
                            <a href="{{ route('courses') }}" class="hover:text-white transition-colors">&larr; Retour aux cours</a>
                            <span class="w-1 h-1 bg-primary-400 rounded-full"></span>
                            <span>{{ $classe->grade->level->name ?? 'N/A' }}</span>
                            <span class="w-1 h-1 bg-primary-400 rounded-full"></span>
                            <span>{{ $classe->grade->name ?? 'N/A' }}</span>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight">{{ $classe->name }}</h1>
                        <p class="mt-4 text-primary-100 text-lg max-w-2xl leading-relaxed">{{ $classe->description ?? 'Programme de formation complet.' }}</p>
                        <div class="mt-6 flex flex-wrap items-center gap-6 text-sm">
                            <div class="flex items-center gap-2 bg-white/10 rounded-full px-4 py-2">
                                <svg class="w-4 h-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <span>{{ $classe->courses->count() }} cours inclus</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 rounded-full px-4 py-2">
                                <svg class="w-4 h-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>{{ $classe->capacity - $classe->enrolled_count }} places restantes</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 text-center lg:text-right bg-white/10 backdrop-blur-sm rounded-2xl p-8 min-w-[280px]">
                        @if($classe->has_bundle_discount)
                            <div class="text-sm text-primary-200 line-through">{{ number_format($classe->total_courses_price, 2) }} DA</div>
                            <div class="text-5xl font-extrabold mt-1">{{ number_format($classe->bundle_discounted_price, 2) }} DA</div>
                            <div class="mt-2 inline-flex items-center gap-1 px-3 py-1 bg-danger-500 text-white text-xs font-bold rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01"/></svg>
                                Économisez {{ $classe->bundle_savings_percent }}%
                            </div>
                        @elseif($classe->bundle_price)
                            <div class="text-5xl font-extrabold">{{ number_format($classe->bundle_price, 2) }} DA</div>
                        @else
                            <div class="text-5xl font-extrabold">{{ number_format($classe->total_courses_price, 2) }} DA</div>
                        @endif
                        <p class="text-primary-200 mt-2">Forfait {{ $classe->courses->count() }} cours</p>
                        @guest
                            <a href="{{ route('register') }}" class="btn-primary btn-lg w-full mt-6">S'inscrire au forfait</a>
                        @elseif(auth()->user()->isStudent())
                            <form method="POST" action="{{ route('courses.enroll-bundle', $classe) }}" class="mt-6">
                                @csrf
                                <button type="submit" class="btn-primary btn-lg w-full">S'inscrire au forfait</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-16 px-4">
            <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-10">
                    {{-- About --}}
                    <div class="bg-white rounded-2xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">À propos de ce programme</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $classe->description ?? 'Programme de formation complet conçu pour offrir une expérience d\'apprentissage optimale.' }}</p>
                    </div>

                    {{-- Included Courses --}}
                    <div class="bg-white rounded-2xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Cours inclus dans ce forfait</h2>
                        <div class="space-y-4">
                            @forelse($classe->courses as $course)
                            <div class="flex items-center justify-between p-5 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors group">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-1">
                                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $course->name }}</h4>
                                        <div class="mt-1 flex items-center gap-3 text-sm text-gray-500">
                                            <span>{{ $course->teacher->full_name ?? 'À déterminer' }}</span>
                                            @if($course->duration)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $course->duration }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 ml-4">
                                    @if($course->price)
                                    <span class="text-sm text-gray-400 line-through">{{ number_format($course->price, 2) }} DA</span>
                                    <p class="text-xs text-gray-400">Inclus dans le forfait</p>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-500">Aucun cours attribué pour le moment.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Schedule --}}
                    @if($classe->schedules->count() > 0)
                    <div class="bg-white rounded-2xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Emploi du temps</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Jour</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Horaire</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Cours</th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Enseignant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classe->schedules as $schedule)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 font-medium text-gray-700">{{ $schedule->day_of_week ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-gray-600">
                                            {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }} - {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-gray-600">{{ $schedule->course->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-gray-600">{{ $schedule->course?->teacher?->full_name ?? 'TBD' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-4">Résumé du forfait</h4>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between pb-3 border-b border-gray-100">
                                <span class="text-gray-500">Prix total des cours</span>
                                <span class="font-medium text-gray-700">{{ number_format($classe->total_courses_price, 2) }} DA</span>
                            </div>
                            @if($classe->has_bundle_discount)
                            <div class="flex justify-between pb-3 border-b border-gray-100">
                                <span class="text-gray-500">Réduction forfait</span>
                                <span class="font-medium text-success-600">-{{ $classe->bundle_savings_percent }}%</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-base">
                                <span class="font-semibold text-gray-900">Total à payer</span>
                                <span class="font-bold text-xl text-danger-600">
                                    {{ number_format($classe->bundle_discounted_price ?? $classe->bundle_price ?? $classe->total_courses_price, 2) }} DA
                                </span>
                            </div>
                            @if($classe->has_bundle_discount)
                            <div class="bg-success-50 rounded-lg p-3 text-center">
                                <span class="text-sm font-semibold text-success-700">Vous économisez {{ number_format($classe->bundle_savings, 2) }} DA</span>
                            </div>
                            @endif
                        </div>
                        @guest
                            <a href="{{ route('register') }}" class="btn-primary btn-lg w-full mt-6">S'inscrire maintenant</a>
                        @elseif(auth()->user()->isStudent())
                            <form method="POST" action="{{ route('courses.enroll-bundle', $classe) }}" class="mt-6">
                                @csrf
                                <button type="submit" class="btn-primary btn-lg w-full">S'inscrire au forfait</button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl p-6 border border-gray-100">
                        <h4 class="font-semibold text-gray-900 mb-3">Informations</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Niveau</span><span class="font-medium text-gray-700">{{ $classe->grade->level->name ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Classe</span><span class="font-medium text-gray-700">{{ $classe->grade->name ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Cours inclus</span><span class="font-medium text-gray-700">{{ $classe->courses->count() }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Capacité</span><span class="font-medium text-gray-700">{{ $classe->capacity ?? 'N/A' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Places restantes</span><span class="font-medium text-gray-700">{{ $classe->remaining_seats }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Related Bundles --}}
        @if(isset($relatedBundles) && $relatedBundles->count() > 0)
        <section class="py-16 px-4 bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Autres programmes similaires</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($relatedBundles as $related)
                    <div class="group bg-white rounded-xl border border-gray-200 hover:border-primary-200 hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <div class="h-2 bg-gradient-to-r from-primary-500 to-primary-600"></div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900">{{ $related->name }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $related->courses->count() }} cours</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="font-bold text-gray-900">{{ number_format($related->bundle_price ?? $related->total_courses_price, 2) }} DA</span>
                                <a href="{{ route('bundles.details', $related->id) }}" class="btn-outline btn-sm">Voir</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- CTA --}}
        <section class="py-20 px-4 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold">Investissez dans votre avenir</h2>
                <p class="mt-3 text-primary-100 text-lg">Rejoignez ce programme complet et bénéficiez d'une formation de qualité à un prix préférentiel.</p>
                @guest
                    <a href="{{ route('register') }}" class="inline-block mt-8 btn-lg bg-white text-primary-600 hover:bg-gray-100 font-semibold px-8 py-3.5 rounded-xl shadow-lg">Créer mon compte</a>
                @endauth
            </div>
        </section>
    </main>

    <footer class="py-8 bg-gray-900 text-gray-400 text-center text-sm">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
