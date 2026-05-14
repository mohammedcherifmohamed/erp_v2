<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Nom arabe</th>
                <th>Code</th>
                <th>Classes</th>
                <th>Statut</th>
                <th>Ordre</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($levels as $level)
                <tr>
                    <td class="font-medium">{{ $level->name }}</td>
                    <td class="text-gray-500">{{ $level->name_ar ?? '-' }}</td>
                    <td><span class="badge-gray">{{ $level->code }}</span></td>
                    <td>{{ $level->grades_count }}</td>
                    <td>
                        <span class="badge-{{ $level->is_active ? 'success' : 'danger' }}">
                            {{ $level->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td>{{ $level->sort_order }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.levels.show', $level) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.levels.edit', $level) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.levels.destroy', $level) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-8">Aucun niveau trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($levels->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $levels->appends(['query' => request('query')])->links() }}
    </div>
@endif