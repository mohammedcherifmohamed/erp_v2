@extends('layouts.app')

@section('title', 'Withdrawal Requests')
@section('page-title', 'Withdrawal Requests')
@section('page-subtitle', 'Manage teacher withdrawal requests')

@section('content')
<div class="card">
    <div class="card-header flex items-center justify-between">
        <form method="GET" class="flex items-center gap-2">
            <select name="status" class="input w-40">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Teacher</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td class="font-medium">{{ $withdrawal->teacher->full_name ?? '-' }}</td>
                            <td>{{ number_format($withdrawal->amount, 2) }}</td>
                            <td>
                                <span class="badge-{{ $withdrawal->status === 'approved' ? 'success' : ($withdrawal->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($withdrawal->method ?? '-') }}</td>
                            <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($withdrawal->status === 'pending')
                                        <form method="POST" action="{{ route('admin.teacher-withdrawals.approve', $withdrawal) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.teacher-withdrawals.reject', $withdrawal) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-sm btn-danger" onclick="return confirm('Reject this withdrawal?')">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No withdrawal requests</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($withdrawals->hasPages())
        <div class="card-footer border-t px-6 py-4">
            {{ $withdrawals->links() }}
        </div>
    @endif
</div>
@endsection
