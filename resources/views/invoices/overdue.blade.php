@extends('layouts.app')

@section('title', 'Factures en retard')
@section('page-title', 'Factures en retard')
@section('page-subtitle', 'Factures dépassant leur date d\'échéance')

@section('content')
<div class="card" data-search="{{ route('admin.invoices.overdue') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher une facture...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="btn-outline">Toutes les factures</a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('invoices._overdue_table')
    </div>
</div>
@endsection
