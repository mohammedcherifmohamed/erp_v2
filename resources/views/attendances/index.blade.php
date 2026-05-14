@extends('layouts.app')

@section('title', 'Registres d\'assiduité')
@section('page-title', 'Assiduité')
@section('page-subtitle', 'Consulter et gérer l\'assiduité')

@section('content')
<div class="card" data-search="{{ route('admin.attendances.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-48" name="query" value="{{ request('query') }}" placeholder="Rechercher un étudiant...">
            <button type="button" class="search-clear btn-secondary hidden" onclick="document.querySelector('.search-input').value='';document.querySelector('.search-input').dispatchEvent(new Event('input'))">✕</button>
        </div>
        <a href="{{ route('admin.attendances.create') }}" class="btn-primary">Marquer l&apos;assiduité</a>
    </div>
    <div class="search-results-container card-body p-0">
        @include('attendances._table')
    </div>
</div>
@endsection
