<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Statut</th>
                <th>Date d'inscription</th>
                <th>Approuvé par</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($enrollments as $enrollment)
                <tr>
                    <td class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</td>
                    <td>{{ $enrollment->classe->name ?? '-' }}</td>
                    <td>
                        <span class="badge-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : ($enrollment->status === 'rejected' ? 'danger' : 'gray')) }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </td>
                    <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                    <td>{{ $enrollment->approvedBy->full_name ?? '-' }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn-sm btn-outline">Voir</a>
                            @if($enrollment->status === 'pending')
                                <form method="POST" action="{{ route('admin.enrollments.approve', $enrollment) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-success">Approuver</button>
                                </form>
                                <form method="POST" action="{{ route('admin.enrollments.reject', $enrollment) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Rejeter cette inscription ?')">Rejeter</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.enrollments.destroy', $enrollment) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Aucune inscription trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($enrollments->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $enrollments->appends(['query' => request('query')])->links() }}
    </div>
@endif
