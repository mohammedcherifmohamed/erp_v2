<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Niveau</th>
                <th>Code</th>
                <th>Cycle</th>
                <th>Classes</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($grades as $grade)
                <tr>
                    <td class="font-medium">{{ $grade->name }}</td>
                    <td><span class="badge-gray">{{ $grade->code }}</span></td>
                    <td>{{ $grade->level->name ?? '-' }}</td>
                    <td>{{ $grade->classes_count }}</td>
                    <td>
                        <span class="badge-{{ $grade->is_active ? 'success' : 'danger' }}">
                            {{ $grade->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.grades.show', $grade) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.grades.edit', $grade) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.grades.destroy', $grade) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Aucun niveau trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($grades->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $grades->appends(['query' => request('query')])->links() }}
    </div>
@endif
