@extends('layouts.app')

@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', 'Détails du quiz')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Questions</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y">
                    @forelse($quiz->questions as $q)
                        <div class="px-6 py-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $q->text }}</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Type : {{ ucfirst(str_replace('_', ' ', $q->type)) }} |
                                        Correct : <span class="font-medium text-success-600">{{ $q->correct_answer }}</span> |
                                        Points : {{ $q->points }}
                                    </p>
                                    @if($q->type === 'mcq' && $q->options)
                                        <p class="text-sm text-gray-400 mt-1">Options : {{ is_array($q->options) ? implode(', ', $q->options) : $q->options }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Aucune question dans ce quiz</p>
                    @endforelse
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Résultats</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Score</th>
                                <th>Total</th>
                                <th>Pourcentage</th>
                                <th>Réussi</th>
                                <th>Soumis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quiz->submissions as $submission)
                                <tr>
                                    <td class="font-medium">{{ $submission->student->full_name ?? '-' }}</td>
                                    <td>{{ $submission->score }}</td>
                                    <td>{{ $submission->total }}</td>
                                    <td>{{ $submission->total > 0 ? round(($submission->score / $submission->total) * 100) : 0 }}%</td>
                                    <td>
                                        <span class="badge-{{ $submission->passed ? 'success' : 'danger' }}">
                                            {{ $submission->passed ? 'Réussi' : 'Échoué' }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">Aucune soumission pour le moment</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Détails</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Cours</p>
                    <p class="font-medium">{{ $quiz->course->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">{{ $quiz->course->class->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Questions</p>
                    <p class="font-medium">{{ $quiz->questions_count }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Points totaux</p>
                    <p class="font-medium">{{ $quiz->total_points }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Points de réussite</p>
                    <p class="font-medium">{{ $quiz->passing_points }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Limite de temps</p>
                    <p class="font-medium">{{ $quiz->time_limit ? $quiz->time_limit . ' min' : 'Aucune limite' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Publié</p>
                    <span class="badge-{{ $quiz->is_published ? 'success' : 'warning' }}">{{ $quiz->is_published ? 'Publié' : 'Brouillon' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Disponible</p>
                    <p class="text-sm">{{ $quiz->available_from ? $quiz->available_from->format('M d, Y H:i') : 'Toujours' }} @if($quiz->available_until) - {{ $quiz->available_until->format('M d, Y H:i') }} @endif</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn-primary">Modifier le quiz</a>
        <a href="{{ route('teacher.quizzes.correct', $quiz) }}" class="btn-success">Corriger les soumissions</a>
        <a href="{{ route('teacher.quizzes.index') }}" class="btn-outline">Retour aux quiz</a>
    </div>
</div>
@endsection
