@extends('layouts.app')

@section('title', 'Horaires')
@section('page-title', 'Horaires')
@section('page-subtitle', 'Gérer les horaires des cours')

@section('content')
<div class="card" data-search="{{ route('admin.schedules.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un horaire...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.schedules.weekly') }}" class="btn-secondary">Vue hebdomadaire</a>
            <a href="{{ route('admin.schedules.create') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nouvel horaire
            </a>
        </div>
    </div>
    <div class="search-results-container card-body p-0">
        @include('schedules._table')
    </div>
</div>
@endsection
