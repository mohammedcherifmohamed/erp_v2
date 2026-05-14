<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>N° Facture</th>
                <th>Étudiant</th>
                <th>Classe</th>
                <th>Montant total</th>
                <th>Reste à payer</th>
                <th>Date d'échéance</th>
                <th>Retard</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="search-results">
            @forelse($invoices as $invoice)
                <tr class="bg-red-50/50">
                    <td class="font-medium">{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->student->full_name ?? '-' }}</td>
                    <td>{{ $invoice->classe->name ?? '-' }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                    <td class="text-danger-600 font-medium">{{ number_format($invoice->remaining_amount, 2) }}</td>
                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge-danger">{{ $invoice->due_date->diffInDays(now()) }} jours</span>
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn-sm btn-outline">Voir</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-8">Aucune facture en retard</td>
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
