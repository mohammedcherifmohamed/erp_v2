@forelse($classes as $class)
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
@empty
    <div class="col-span-full text-center py-12 text-gray-500">
        <p class="text-lg">Aucune section trouvée</p>
        <p class="text-sm mt-1">Essayez d'ajuster votre recherche ou vos filtres</p>
    </div>
@endforelse
