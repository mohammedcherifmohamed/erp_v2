<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Montant</th>
                <th>Méthode</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($withdrawals as $withdrawal)
                <tr>
                    <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                    <td class="font-medium">{{ number_format($withdrawal->amount, 2) }}</td>
                    <td>{{ ucfirst($withdrawal->method ?? '-') }}</td>
                    <td>
                        <span class="badge-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ $withdrawal->status === 'approved' ? 'Approuvé' : ($withdrawal->status === 'pending' ? 'En attente' : 'Rejeté') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-500 py-8">Aucune demande de retrait</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($withdrawals->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $withdrawals->appends(['query' => request('query')])->links() }}
    </div>
@endif
