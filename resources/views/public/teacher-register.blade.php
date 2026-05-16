<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }} | Rejoignez notre équipe</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white">
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
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-white hover:text-primary-200 transition-colors">{{ auth()->user()->full_name }}</a>
                            <div class="w-8 h-8 bg-white/20 text-white rounded-full flex items-center justify-center text-sm font-bold">{{ auth()->user()->initials }}</div>
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
        <section class="pt-32 pb-20 bg-gradient-to-br from-primary-600 to-primary-900 text-white px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl lg:text-5xl font-extrabold">Rejoignez notre équipe pédagogique</h1>
                <p class="mt-4 text-primary-100 text-lg max-w-2xl mx-auto">Façonnez l'avenir de l'éducation. Nous recherchons des éducateurs passionnés pour inspirer la prochaine génération.</p>
            </div>
        </section>

        {{-- Benefits --}}
        <section class="py-16 px-4 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Pourquoi enseigner avec nous ?</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Horaires flexibles</h3>
                        <p class="mt-2 text-sm text-gray-500">Concevez votre emploi du temps d'enseignement qui correspond à votre style de vie et vos engagements.</p>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Rémunération compétitive</h3>
                        <p class="mt-2 text-sm text-gray-500">Gagnez un salaire compétitif avec des primes de performance et des opportunités d'évolution.</p>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-warning-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Ressources modernes</h3>
                        <p class="mt-2 text-sm text-gray-500">Accédez à notre LMS et outils pédagogiques de pointe pour enrichir vos cours.</p>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-danger-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Communauté solidaire</h3>
                        <p class="mt-2 text-sm text-gray-500">Rejoignez un réseau d'éducateurs dévoués qui collaborent et se soutiennent mutuellement.</p>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Développement professionnel</h3>
                        <p class="mt-2 text-sm text-gray-500">Formation continue, ateliers et opportunités d'avancement de carrière.</p>
                    </div>
                    <div class="card p-6 text-center">
                        <div class="w-14 h-14 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">Avantages santé</h3>
                        <p class="mt-2 text-sm text-gray-500">Assurance maladie complète et programmes de bien-être pour vous et votre famille.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Application Form --}}
        <section class="py-16 px-4">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-4">Postulez maintenant</h2>
                <p class="text-gray-500 text-center mb-10">Remplissez le formulaire ci-dessous et nous vous répondrons sous 48 heures.</p>

                <form action="{{ route('teacher.register.submit') }}" method="POST" enctype="multipart/form-data" class="card p-8 space-y-6">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="label">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="input w-full @error('first_name') border-red-500 @enderror" placeholder="Jean">
                            @error('first_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="last_name" class="label">Nom <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="input w-full @error('last_name') border-red-500 @enderror" placeholder="Dupont">
                            @error('last_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="label">Adresse e-mail <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="input w-full @error('email') border-red-500 @enderror" placeholder="jean@exemple.com">
                            @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="label">Numéro de téléphone <span class="text-red-500">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="input w-full @error('phone') border-red-500 @enderror" placeholder="+212 6XX XXX XXX">
                            @error('phone') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="gender" class="label">Genre <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" class="input w-full @error('gender') border-red-500 @enderror">
                                <option value="">Sélectionner le genre</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('gender') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="specialization" class="label">Spécialisation <span class="text-red-500">*</span></label>
                            <select id="specialization" name="specialization" class="input w-full @error('specialization') border-red-500 @enderror">
                                <option value="">Sélectionner la spécialisation</option>
                                @foreach($specializations as $spec)
                                    <option value="{{ $spec }}" {{ old('specialization') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                @endforeach
                            </select>
                            @error('specialization') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="experience_years" class="label">Années d'expérience <span class="text-red-500">*</span></label>
                            <input type="number" id="experience_years" name="experience_years" value="{{ old('experience_years') }}" min="0" max="50" class="input w-full @error('experience_years') border-red-500 @enderror" placeholder="5">
                            @error('experience_years') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="cv" class="label">Télécharger le CV <span class="text-red-500">*</span></label>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" class="input w-full @error('cv') border-red-500 @enderror">
                            <p class="text-xs text-gray-400 mt-1">Accepté : PDF, DOC, DOCX (max 5 Mo)</p>
                            @error('cv') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="label">Lettre de motivation</label>
                        <textarea id="message" name="message" rows="5" class="input w-full @error('message') border-red-500 @enderror" placeholder="Dites-nous pourquoi vous souhaitez rejoindre notre équipe...">{{ old('message') }}</textarea>
                        @error('message') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="btn-primary btn-lg px-12">Soumettre la candidature</button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <footer class="py-8 bg-gray-900 text-gray-400 text-center text-sm">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} SchoolERP. Tous droits réservés.
        </div>
    </footer>
</body>
</html>
