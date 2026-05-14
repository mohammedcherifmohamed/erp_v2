@extends('layouts.app')

@section('title', 'My Withdrawals')
@section('page-title', 'Withdrawals')
@section('page-subtitle', 'Manage your withdrawal requests')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-3 gap-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-primary-600">{{ number_format($walletBalance ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Wallet Balance</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-success-600">{{ number_format($totalApproved ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total Withdrawn</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-warning-600">{{ $pendingCount ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Pending Requests</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Request Withdrawal</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.withdrawals.store') }}" class="flex gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label for="amount" class="label">Amount <span class="text-danger-500">*</span></label>
                    <input id="amount" type="number" step="0.01" name="amount" required class="input @error('amount') input-error @enderror" max="{{ $walletBalance ?? 0 }}" placeholder="0.00">
                    @error('amount') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex-1">
                    <label for="method" class="label">Method <span class="text-danger-500">*</span></label>
                    <select id="method" name="method" required class="input @error('method') input-error @enderror">
                        <option value="">Select Method</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cash">Cash</option>
                        <option value="cheque">Cheque</option>
                        <option value="mobile_wallet">Mobile Wallet</option>
                    </select>
                    @error('method') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary">Request</button>
            </form>
        </div>
    </div>

    <div class="card" data-search="{{ route('teacher.withdrawals.index') }}">
        <div class="card-header flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Historique des retraits</h3>
            <div class="flex items-center gap-2">
                <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher...">
                <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
            </div>
        </div>
        <div class="search-results-container card-body p-0">
            @include('teacher-withdrawals._teacher_table')
        </div>
    </div>
</div>
@endsection
