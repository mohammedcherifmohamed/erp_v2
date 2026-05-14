@extends('layouts.app')

@section('title', 'Enseignants')
@section('page-title', 'Enseignants')
@section('page-subtitle', 'Gérer les comptes enseignants')

@section('content')
<div class="card" data-search="{{ route('admin.teachers.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un enseignant...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvel enseignant
        </a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('teachers._table')
    </div>
</div>
@endsection
