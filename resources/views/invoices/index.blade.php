@extends('layouts.app')

@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', 'Gérer les factures des étudiants')

@section('content')
<div class="card" data-search="{{ route('admin.invoices.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2 flex-wrap">
            <input type="text" class="search-input input w-48" name="query" value="{{ request('query') }}" placeholder="N° de facture...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
            <a href="{{ route('admin.invoices.overdue') }}" class="btn-danger">En retard</a>
        </div>
        <a href="{{ route('admin.invoices.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvelle facture
        </a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('invoices._table')
    </div>
</div>
@endsection
