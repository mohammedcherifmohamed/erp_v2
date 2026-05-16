<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Type</th>
                <th>Classe / Cours</th>
                <th>Statut</th>
                <th>Date</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($enrollments as $enrollment)
                <tr>
                    <td class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</td>
                    <td>
                        @if($enrollment->course_id)
                            <span class="badge-primary">Cours</span>
                        @else
                            <span class="badge-success">Forfait</span>
                        @endif
                    </td>
                    <td>
                        @if($enrollment->course_id)
                            {{ $enrollment->course->name ?? '-' }}
                        @else
                            {{ $enrollment->classe->name ?? '-' }} ({{ $enrollment->classe->courses->count() ?? 0 }} cours)
                        @endif
                    </td>
                    <td>
                        <span class="badge-{{ $enrollment->status === 'approved' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : ($enrollment->status === 'rejected' ? 'danger' : 'gray')) }}">
                            @switch($enrollment->status)
                                @case('approved') Approuvé @break
                                @case('pending') En attente @break
                                @case('rejected') Refusé @break
                                @default {{ $enrollment->status }}
                            @endswitch
                        </span>
                    </td>
                    <td>{{ $enrollment->created_at->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn-sm btn-outline">Voir</a>
                            @if($enrollment->status === 'pending')
                                <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn-sm btn-success">Approuver</a>
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
