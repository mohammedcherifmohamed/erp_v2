@extends('layouts.app')

@section('title', 'My Invoices')
@section('page-title', 'My Invoices')
@section('page-subtitle', 'View your invoices')

@section('content')
<div class="card">
    <div class="card-header">
        <form method="GET" class="flex items-center gap-2">
            <select name="status" class="input w-36">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
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
                                <a href="{{ route('student.invoices.show', $invoice) }}" class="btn-sm btn-outline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-8">No invoices found</td>
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
@endsection
