<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Enfants</th>
                <th>Relation</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($parents as $parent)
                <tr>
                    <td class="font-medium">{{ $parent->full_name }}</td>
                    <td class="text-gray-500">{{ $parent->email }}</td>
                    <td>{{ $parent->phone ?? '-' }}</td>
                    <td>{{ $parent->children_count }}</td>
                    <td>{{ $parent->relationship ?? '-' }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.parents.show', $parent) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.parents.edit', $parent) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.parents.destroy', $parent) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Aucun parent trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($parents->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $parents->appends(['query' => request('query')])->links() }}
    </div>
@endif
