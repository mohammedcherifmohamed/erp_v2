<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Cours</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Marqué par</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($attendances as $attendance)
                <tr>
                    <td class="font-medium">{{ $attendance->student->full_name ?? '-' }}</td>
                    <td>{{ $attendance->course->name ?? '-' }}</td>
                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : ($attendance->status === 'late' ? 'warning' : 'gray')) }}">
                            {{ $attendance->status === 'present' ? 'Présent' : ($attendance->status === 'absent' ? 'Absent' : ($attendance->status === 'late' ? 'Retard' : 'Excusé')) }}
                        </span>
                    </td>
                    <td>{{ $attendance->markedBy->full_name ?? '-' }}</td>
                    <td class="text-right">
                        <form method="POST" action="{{ route('admin.attendances.destroy', $attendance) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Aucun enregistrement d'assiduité trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($attendances->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $attendances->appends(['query' => request('query')])->links() }}
    </div>
@endif
