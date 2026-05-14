@extends('layouts.app')

@section('title', 'Withdrawal Details')
@section('page-title', 'Withdrawal Details')
@section('page-subtitle', $withdrawal->teacher->full_name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Withdrawal Request</h3>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Teacher</p>
                    <p class="font-medium">{{ $withdrawal->teacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $withdrawal->teacher->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-lg font-semibold">{{ number_format($withdrawal->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'pending' ? 'warning' : 'danger') }} inline-block mt-1">
                        {{ ucfirst($withdrawal->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Method</p>
                    <p class="font-medium">{{ ucfirst($withdrawal->method ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Requested On</p>
                    <p class="font-medium">{{ $withdrawal->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            @if($withdrawal->notes)
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $withdrawal->notes }}</p>
                </div>
            @endif
            @if($withdrawal->approved_by)
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Approved By</p>
                    <p class="font-medium">{{ $withdrawal->approvedBy->full_name ?? '-' }}</p>
                    @if($withdrawal->approved_at)
                        <p class="text-sm text-gray-500">on {{ $withdrawal->approved_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            @endif
        </div>
        <div class="card-footer border-t flex items-center justify-end gap-3 px-6 py-4">
            @if($withdrawal->status === 'pending')
                <form method="POST" action="{{ route('admin.teacher-withdrawals.approve', $withdrawal) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary">Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.teacher-withdrawals.reject', $withdrawal) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-danger" onclick="return confirm('Reject this withdrawal?')">Reject</button>
                </form>
            @endif
            <a href="{{ route('admin.teacher-withdrawals.index') }}" class="btn-outline">Back</a>
        </div>
    </div>
</div>
@endsection
