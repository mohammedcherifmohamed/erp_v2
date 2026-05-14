<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Genre</th>
                <th>Spécialisation</th>
                <th>Cours</th>
                <th>Solde</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($teachers as $teacher)
                <tr>
                    <td class="font-medium">{{ $teacher->full_name }}</td>
                    <td class="text-gray-500">{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone ?? '-' }}</td>
                    <td>{{ ucfirst($teacher->gender ?? '-') }}</td>
                    <td>{{ $teacher->specialization ?? '-' }}</td>
                    <td>{{ $teacher->courses_count }}</td>
                    <td>{{ number_format($teacher->wallet_balance ?? 0, 2) }}</td>
                    <td>
                        <span class="badge-{{ $teacher->is_active ? 'success' : 'danger' }}">
                            {{ $teacher->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-8">Aucun enseignant trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($teachers->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $teachers->appends(['query' => request('query')])->links() }}
    </div>
@endif
