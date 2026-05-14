<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Cible</th>
                <th>Statut</th>
                <th>Date</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($announcements as $announcement)
                <tr>
                    <td class="font-medium">{{ $announcement->title }}</td>
                    <td>
                        @if($announcement->is_global)
                            <span class="badge-primary">Global</span>
                        @else
                            {{ $announcement->classe->name ?? '-' }}
                        @endif
                    </td>
                    <td>
                        <span class="badge-{{ $announcement->is_published ? 'success' : 'warning' }}">
                            {{ $announcement->is_published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </td>
                    <td>{{ $announcement->created_at->format('M d, Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('teacher.announcements.show', $announcement) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('teacher.announcements.edit', $announcement) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('teacher.announcements.destroy', $announcement) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-8">Aucune annonce trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($announcements->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $announcements->appends(['query' => request('query')])->links() }}
    </div>
@endif
