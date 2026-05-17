<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Enseignant</th>
                <th>Type de contrat</th>
                <th>Taux</th>
                <th>Cours/Classe</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($contracts as $contract)
                <tr>
                    <td class="font-medium">{{ $contract->teacher->full_name ?? '-' }}</td>
                    <td>
                        @php $labels = ['percentage' => 'نسبه مئويه', 'per_session' => 'بالحصه', 'per_student' => 'بعدد التلاميذ', 'monthly' => 'شهريا']; @endphp
                        <span class="badge-gray">{{ $labels[$contract->contract_type] ?? $contract->contract_type }}</span>
                    </td>
                    <td>{{ number_format($contract->rate, 2) }}</td>
                    <td>{{ $contract->course->name ?? $contract->classe->name ?? '-' }}</td>
                    <td>
                        <span class="badge-{{ $contract->is_active ? 'success' : 'danger' }}">
                            {{ $contract->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.teacher-contracts.edit', $contract) }}" class="btn-sm btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('admin.teacher-contracts.destroy', $contract) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">Aucun contrat trouvé</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($contracts->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $contracts->appends(['query' => request('query')])->links() }}
    </div>
@endif
