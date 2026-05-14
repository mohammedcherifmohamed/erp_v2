<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Parent</th>
                <th>Inscriptions</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($students as $student)
                <tr>
                    <td class="font-medium">{{ $student->full_name }}</td>
                    <td class="text-gray-500">{{ $student->email }}</td>
                    <td>{{ $student->phone ?? '-' }}</td>
                    <td>{{ $student->parent->full_name ?? '-' }}</td>
                    <td>{{ $student->enrollments_count }}</td>
                    <td>
                        <span class="badge-{{ $student->is_active ? 'success' : 'danger' }}">
                            {{ $student->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.students.show', $student) }}" class="btn-sm btn-outline">Voir</a>
                            <a href="{{ route('admin.students.edit', $student) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-500 py-8">Aucun étudiant trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($students->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $students->appends(['query' => request('query')])->links() }}
    </div>
@endif
