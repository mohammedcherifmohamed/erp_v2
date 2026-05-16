@extends('layouts.app')

@section('title', 'Mes Quiz')
@section('page-title', 'Mes Quiz')
@section('page-subtitle', 'Quiz disponibles pour vos cours')

@section('content')
<div class="card" data-search="{{ route('student.quizzes.index') }}">
    <div class="card-header flex items-center justify-between">
        <div class="flex items-center gap-2">
            <input type="text" class="search-input input w-64" name="query" value="{{ request('query') }}" placeholder="Rechercher un quiz...">
        </div>
    </div>
    <div class="search-results-container card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Cours</th>
                        <th>Enseignant</th>
                        <th>Questions</th>
                        <th>Points</th>
                        <th>Disponible</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="font-medium">{{ $quiz->title }}</td>
                            <td>{{ $quiz->course->name ?? '-' }}</td>
                            <td>{{ $quiz->teacher->full_name ?? '-' }}</td>
                            <td>{{ $quiz->questions->count() }}</td>
                            <td>{{ $quiz->total_points }}</td>
                            <td class="text-sm text-gray-500">
                                @if($quiz->available_from && $quiz->available_until)
                                    {{ $quiz->available_from->format('d/m/Y') }} - {{ $quiz->available_until->format('d/m/Y') }}
                                @else
                                    Permanent
                                @endif
                            </td>
                            <td class="text-right">
                                <a href="{{ route('student.quizzes.take', $quiz) }}" class="btn-sm btn-primary">Passer le quiz</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-8">Aucun quiz disponible pour le moment</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($quizzes->hasPages())
        <div class="card-footer border-t px-6 py-4">
            {{ $quizzes->links() }}
        </div>
    @endif
</div>
@endsection