@extends('layouts.app')

@section('title', 'Mes cours - Présence')
@section('page-title', 'Présence')
@section('page-subtitle', 'Gérer la présence pour vos cours')

@section('content')
<div class="space-y-6">
    @forelse($courses as $course)
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $course->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $course->code }} - {{ $course->class->name ?? '' }} ({{ $course->sessions_count }} sessions)</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.attendances.mark', $course) }}" class="btn-sm btn-primary">Marquer les présences</a>
                    <a href="{{ route('teacher.attendances.history', $course) }}" class="btn-sm btn-outline">Historique</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-gray-500 py-8">
                Aucun cours ne vous est assigné
            </div>
        </div>
    @endforelse
</div>
@endsection
