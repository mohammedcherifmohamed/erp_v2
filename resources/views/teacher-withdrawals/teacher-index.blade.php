@extends('layouts.app')

@section('title', 'Mes retraits')
@section('page-title', 'Retraits')
@section('page-subtitle', 'Gérer vos demandes de retrait')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-3 gap-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-primary-600">{{ number_format($walletBalance ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Solde du portefeuille</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-success-600">{{ number_format($totalApproved ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Total retiré</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-warning-600">{{ $pendingCount ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Demandes en attente</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Demander un retrait</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.withdrawals.store') }}" class="flex gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label for="amount" class="label">Montant <span class="text-danger-500">*</span></label>
                    <input id="amount" type="number" step="0.01" name="amount" required class="input @error('amount') input-error @enderror" max="{{ $walletBalance ?? 0 }}" placeholder="0.00">
                    @error('amount') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex-1">
                    <label for="method" class="label">Méthode <span class="text-danger-500">*</span></label>
                    <select id="method" name="method" required class="input @error('method') input-error @enderror">
                        <option value="">Sélectionner la méthode</option>
                        <option value="bank_transfer">Virement bancaire</option>
                        <option value="cash">Espèces</option>
                        <option value="cheque">Chèque</option>
                        <option value="mobile_wallet">Portefeuille mobile</option>
                    </select>
                    @error('method') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-primary">Demander</button>
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
