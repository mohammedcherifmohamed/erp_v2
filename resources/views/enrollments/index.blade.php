@extends('layouts.app')

@section('title', 'Inscriptions')
@section('page-title', 'Inscriptions')
@section('page-subtitle', 'Gérer les inscriptions des étudiants')

@section('content')
<div class="card" data-search="{{ route('admin.enrollments.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un étudiant...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.enrollments.pending') }}" class="btn-warning">Inscriptions en attente</a>
        </div>
    </div>
    <div class="search-results-container card-body p-0">
        @include('enrollments._table')
    </div>
</div>
@endsection
