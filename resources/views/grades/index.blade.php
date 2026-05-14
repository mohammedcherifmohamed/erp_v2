@extends('layouts.app')

@section('title', 'Niveaux')
@section('page-title', 'Niveaux')
@section('page-subtitle', 'Gérer les niveaux académiques')

@section('content')
<div class="card" data-search="{{ route('admin.grades.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un niveau...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('admin.grades.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouveau niveau
        </a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('grades._table')
    </div>
</div>
@endsection
