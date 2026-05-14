<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Cours</th>
                <th>Code</th>
                <th>Classe</th>
                <th>Enseignant</th>
                <th>Horaires</th>
                <th>Quiz</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($courses as $course)
                <tr>
                    <td class="font-medium">{{ $course->name }}</td>
                    <td><span class="badge-gray">{{ $course->code }}</span></td>
                    <td>{{ $course->classe->name ?? '-' }}</td>
                    <td>{{ $course->teacher->full_name ?? '-' }}</td>
                    <td>{{ $course->schedules_count }}</td>
                    <td>{{ $course->quizzes_count }}</td>
                    <td>
                        <span class="badge-{{ $course->is_active ? 'success' : 'danger' }}">
                            {{ $course->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.courses.show', $course) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-8">Aucun cours trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($courses->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $courses->appends(['query' => request('query')])->links() }}
    </div>
@endif
