@extends('layouts.app')

@section('title', 'Inscriptions en attente')
@section('page-title', 'Inscriptions en attente')
@section('page-subtitle', 'Examiner et traiter les demandes d\'inscription')

@section('content')
<div class="card" data-search="{{ route('admin.enrollments.pending') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un étudiant...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('admin.enrollments.index') }}" class="btn-outline">Toutes les inscriptions</a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('enrollments._pending_table')
    </div>
</div>
@endsection
