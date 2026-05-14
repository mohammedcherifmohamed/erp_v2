<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Montant total</th>
                <th>Montant payé</th>
                <th>Reste à payer</th>
                <th>Statut</th>
                <th>Date d'échéance</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($invoices as $invoice)
                <tr>
                    <td class="font-medium">{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->student->full_name ?? '-' }}</td>
                    <td>{{ $invoice->classe->name ?? '-' }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="text-success-600 font-medium">{{ number_format($invoice->paid_amount, 2) }}</td>
                    <td class="text-danger-600 font-medium">{{ number_format($invoice->remaining_amount, 2) }}</td>
                    <td>
                        <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : ($invoice->status === 'pending' ? 'warning' : 'gray')) }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn-sm btn-outline">Voir</a>
                            <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500 py-8">Aucune facture trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($invoices->hasPages())
    <div class="search-pagination card-footer border-t px-6 py-4">
        {{ $invoices->appends(['query' => request('query')])->links() }}
    </div>
@endif
