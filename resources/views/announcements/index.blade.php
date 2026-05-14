@extends('layouts.app')

@section('title', 'Annonces')
@section('page-title', 'Annonces')
@section('page-subtitle', 'Gérer les annonces')

@section('content')
<div class="card" data-search="{{ route('teacher.announcements.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher une annonce...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('teacher.announcements.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nouvelle annonce
        </a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('announcements._table')
    </div>
</div>
@endsection
