@extends('layouts.app')

@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', "Factures pour {{ $student->full_name }}")

@section('content')
<div class="space-y-6">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">{{ $student->full_name }} - Factures</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Section</th>
                            <th>Total</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Date d'échéance</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="font-medium">{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->classe->name ?? '-' }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-success-600 font-medium">{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td class="text-danger-600 font-medium">{{ number_format($invoice->remaining_amount, 2) }}</td>
                                <td>
                                    <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('parent.children.invoices.show', ['student' => $student->id, 'invoice' => $invoice->id]) }}" class="btn-sm btn-outline">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-8">Aucune facture trouvée pour cet étudiant</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($invoices->hasPages())
            <div class="card-footer border-t px-6 py-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <a href="{{ route('parent.children') }}" class="btn-outline">Retour aux enfants</a>
</div>
@endsection