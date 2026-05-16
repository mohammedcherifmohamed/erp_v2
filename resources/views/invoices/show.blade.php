@extends('layouts.app')

@section('title', 'Facture #' . $invoice->invoice_number)
@section('page-title', 'Facture #' . $invoice->invoice_number)
@section('page-subtitle', 'Détails de la facture')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Détails de la facture</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Étudiant</p>
                        <p class="font-medium">{{ $invoice->student->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Section</p>
                        <p class="font-medium">{{ $invoice->classe->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">N° Facture</p>
                        <p class="font-medium">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date d'échéance</p>
                        <p class="font-medium">{{ $invoice->due_date->format('M d, Y') }}</p>
                    </div>
                </div>
                @if($invoice->description)
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="text-sm text-gray-700">{{ $invoice->description }}</p>
                    </div>
                @endif
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Montant initial</span>
                        <span class="text-lg font-bold">{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    @if($invoice->reduction_amount > 0)
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-gray-500">Réduction</span>
                            <span class="text-lg font-bold text-danger-600">-{{ number_format($invoice->reduction_amount, 2) }}</span>
                        </div>
                        @if($invoice->reduction_reason)
                            <div class="mt-1 text-xs text-gray-400">Motif : {{ $invoice->reduction_reason }}</div>
                        @endif
                    @endif
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-gray-500">Montant net</span>
                        <span class="text-lg font-bold">{{ number_format($invoice->netAmount(), 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-gray-500">Payé</span>
                        <span class="text-lg font-bold text-success-600">{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1 border-t pt-1">
                        <span class="font-medium">Reste</span>
                        <span class="text-lg font-bold text-danger-600">{{ number_format($invoice->remaining_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Paiements</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Référence</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="font-medium text-success-600">{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst($payment->method ?? '-') }}</td>
                                    <td>{{ $payment->reference ?? '-' }}</td>
                                    <td class="text-sm text-gray-500">{{ $payment->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-gray-500 py-8">Aucun paiement enregistré</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($invoice->remaining_amount > 0)
                <div class="card-header border-t">
                    <h3 class="font-semibold text-gray-900">Appliquer une réduction</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.invoices.reduction', $invoice) }}" class="grid grid-cols-3 gap-3">
                        @csrf
                        <div>
                            <input type="number" step="0.01" name="reduction_amount" placeholder="Montant de la réduction" class="input" max="{{ $invoice->total_amount }}" value="{{ $invoice->reduction_amount ?? 0 }}">
                        </div>
                        <div>
                            <input type="text" name="reduction_reason" placeholder="Motif (ex: réduction de fidélité)" class="input" value="{{ $invoice->reduction_reason ?? '' }}">
                        </div>
                        <button type="submit" class="btn-warning">{{ $invoice->reduction_amount > 0 ? 'Modifier la réduction' : 'Appliquer la réduction' }}</button>
                    </form>
                </div>
            @endif

            @if($invoice->remaining_amount > 0)
                <div class="card-header border-t">
                    <h3 class="font-semibold text-gray-900">Enregistrer un paiement</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.invoices.payments', $invoice) }}" class="grid grid-cols-4 gap-3">
                        @csrf
                        <div>
                            <input type="number" step="0.01" name="amount" placeholder="Montant" class="input" max="{{ $invoice->remaining_amount }}" required>
                        </div>
                        <div>
                            <select name="method" class="input" required>
                                <option value="">Méthode</option>
                                <option value="cash">Espèces</option>
                                <option value="card">Carte</option>
                                <option value="bank_transfer">Virement bancaire</option>
                                <option value="cheque">Chèque</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="reference" placeholder="Référence" class="input">
                        </div>
                        <button type="submit" class="btn-primary">Ajouter le paiement</button>
                    </form>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Résumé</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }} inline-block mt-1">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="text-sm">{{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date d'échéance</p>
                    <p class="text-sm font-medium">{{ $invoice->due_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jours de retard</p>
                    <p class="text-sm font-medium text-danger-600">{{ $invoice->due_date->isPast() ? $invoice->due_date->diffInDays(now()) : 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.invoices.index') }}" class="btn-outline">Retour aux factures</a>
    </div>
</div>
@endsection
