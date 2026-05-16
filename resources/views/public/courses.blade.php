<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School ERP') }} | Cours</title>
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
                    <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">SchoolERP</a>
                </div>
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
                    <a href="{{ url('/') }}" class="hover:text-primary-600 transition-colors">Accueil</a>
                    <a href="{{ route('courses') }}" class="text-primary-600">Cours</a>
                    <a href="{{ route('teacher.register') }}" class="hover:text-primary-600 transition-colors">Enseigner</a>
                </nav>
                <div class="flex items-center gap-4">
                    @guest
                        <div class="hidden sm:flex items-center gap-1">
                            <a href="{{ route('teacher.login') }}" class="text-sm text-gray-500 hover:text-primary-600 transition-colors px-3 py-2">Enseignant</a>
                        </div>
                        <a href="{{ route('login') }}" class="btn-outline">Connexion</a>
                        <a href="{{ route('register') }}" class="btn-primary">S'inscrire</a>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors">{{ auth()->user()->full_name }}</a>
                            <div class="w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">{{ auth()->user()->initials }}</div>
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
        <section class="pt-32 pb-16 bg-gradient-to-br from-primary-600 to-primary-900 text-white">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold">Explorez nos cours</h1>
                <p class="mt-4 text-primary-100 text-lg">Trouvez la classe parfaite pour votre parcours éducatif</p>
                <div class="mt-8 max-w-xl mx-auto">
                    <input type="text" id="course-search" class="w-full px-4 py-3 rounded-xl text-gray-900" placeholder="Rechercher des cours..." value="{{ request('query') }}">
                </div>
            </div>
        </section>

        <section class="py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-3 mb-8">
                    <select id="filter-level" class="input w-48">
                        <option value="">Tous les niveaux</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                    <select id="filter-sort" class="input w-48">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix : croissant</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix : décroissant</option>
                    </select>
                </div>

                <div id="courses-grid" data-search="{{ route('courses') }}" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @include('public._courses_grid')
                </div>

                @if($classes->hasPages())
                    <div class="mt-8" id="courses-pagination">
                        {{ $classes->links() }}
                    </div>
                @endif
            </div>
        </section>
    </main>

    <footer class="py-8 bg-gray-900 text-gray-400 text-center text-sm">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} SchoolERP. Tous droits réservés.
        </div>
    </footer>

    <script>
        let debounceTimer;
        function fetchCourses() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const query = document.getElementById('course-search').value;
                const levelId = document.getElementById('filter-level').value;
                const sort = document.getElementById('filter-sort').value;
                const params = new URLSearchParams({ query, level_id: levelId, sort });
                fetch('{{ route('courses') }}?' + params, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById('courses-grid').innerHTML = html;
                    });
            }, 400);
        }
        document.getElementById('course-search')?.addEventListener('input', fetchCourses);
        document.getElementById('filter-level')?.addEventListener('change', fetchCourses);
        document.getElementById('filter-sort')?.addEventListener('change', fetchCourses);
    </script>
</body>
</html>
