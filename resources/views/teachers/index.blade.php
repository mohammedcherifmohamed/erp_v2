@extends('layouts.app')

@section('title', 'Enseignants')
@section('page-title', 'Enseignants')
@section('page-subtitle', 'Gérer les comptes enseignants')

@section('content')
@php $pendingCount = \App\Models\User::byRole('teacher')->whereHas('teacherProfile', fn($q) => $q->pending())->count(); @endphp
@if($pendingCount > 0)
    <div class="card p-4 mb-4 bg-warning-50 border border-warning-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <span class="text-sm text-warning-800 font-medium">{{ $pendingCount }} candidature(s) enseignant en attente d'approbation</span>
            </div>
            <a href="{{ route('admin.teachers.pending') }}" class="btn-sm btn-warning">Voir les candidatures</a>
        </div>
    </div>
@endif
<div class="card" data-search="{{ route('admin.teachers.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un enseignant...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.teachers.pending') }}" class="btn-warning">
                Candidatures en attente
            </a>
            <a href="{{ route('admin.teachers.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvel enseignant
            </a>
        </div>
    </div>
    <div class="search-results-container card-body p-0">
        @include('teachers._table')
    </div>
</div>
@endsection
