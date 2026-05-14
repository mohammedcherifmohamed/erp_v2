@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-subtitle', "Invoices for {{ $student->full_name }}")

@section('content')
<div class="space-y-6">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">{{ $student->full_name }} - Invoices</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Class</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Due Date</th>
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
                                    <a href="{{ route('parent.children.invoices.show', ['student' => $student->id, 'invoice' => $invoice->id]) }}" class="btn-sm btn-outline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-8">No invoices found for this student</td>
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

    <a href="{{ route('parent.children') }}" class="btn-outline">Back to Children</a>
</div>
@endsection