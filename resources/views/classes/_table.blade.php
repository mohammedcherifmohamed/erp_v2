<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Classe</th>
                <th>Section</th>
                <th>Niveau / Cycle</th>
                <th>Capacité</th>
                <th>Inscrits</th>
                <th>Prix</th>
                <th>Professeur principal</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($classes as $class)
                <tr>
                    <td class="font-medium">{{ $class->name }}</td>
                    <td>{{ $class->section ?? '-' }}</td>
                    <td>{{ $class->grade->name ?? '-' }} / {{ $class->grade->level->name ?? '-' }}</td>
                    <td>{{ $class->capacity }}</td>
                    <td>{{ $class->enrollments_count ?? 0 }}</td>
                    <td>{{ number_format($class->price, 2) }}</td>
                    <td>{{ $class->homeroomTeacher->full_name ?? '-' }}</td>
                    <td>
                        <span class="badge-{{ $class->is_active ? 'success' : 'danger' }}">
                            {{ $class->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.classes.show', $class) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.classes.edit', $class) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-8">Aucune classe trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($classes->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $classes->appends(['query' => request('query')])->links() }}
    </div>
@endif
