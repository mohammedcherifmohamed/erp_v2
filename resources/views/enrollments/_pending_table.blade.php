<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Niveau / Cycle</th>
                <th>Date</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($enrollments as $enrollment)
                <tr class="bg-yellow-50/50">
                    <td class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</td>
                    <td>{{ $enrollment->classe->name ?? '-' }}</td>
                    <td>{{ $enrollment->classe->grade->name ?? '-' }} / {{ $enrollment->classe->grade->level->name ?? '-' }}</td>
                    <td>{{ $enrollment->created_at->diffForHumans() }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="btn-sm btn-outline">Examiner</a>
                            <form method="POST" action="{{ route('admin.enrollments.approve', $enrollment) }}" class="inline">
                                @csrf
                                <button type="submit" class="btn-sm btn-success">Approuver</button>
                            </form>
                            <form method="POST" action="{{ route('admin.enrollments.reject', $enrollment) }}" class="inline">
                                @csrf
                                <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Rejeter cette inscription ?')">Rejeter</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-gray-500 py-8">Aucune inscription en attente</td>
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
