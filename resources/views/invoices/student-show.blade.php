@extends('layouts.app')

@section('title', 'Invoice #' . $invoice->invoice_number)
@section('page-title', 'Invoice #' . $invoice->invoice_number)
@section('page-subtitle', 'Invoice details')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Invoice Details</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="font-medium">{{ $invoice->classe->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Invoice #</p>
                        <p class="font-medium">{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Due Date</p>
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
                        <span class="text-gray-500">Total Amount</span>
                        <span class="text-lg font-bold">{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-gray-500">Paid</span>
                        <span class="text-lg font-bold text-success-600">{{ number_format($invoice->paid_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mt-1 border-t pt-1">
                        <span class="font-medium">Remaining</span>
                        <span class="text-lg font-bold text-danger-600">{{ number_format($invoice->remaining_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Payments</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="font-medium text-success-600">{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ ucfirst($payment->method ?? '-') }}</td>
                                    <td>{{ $payment->reference ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-gray-500 py-8">No payments recorded</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Summary</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }} inline-block mt-1">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="text-sm">{{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Due Date</p>
                    <p class="text-sm font-medium">{{ $invoice->due_date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('student.invoices.index') }}" class="btn-outline">Back to Invoices</a>
    </div>
</div>
@endsection
