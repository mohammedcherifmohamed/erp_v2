@extends('layouts.app')

@section('title', $course->name)
@section('page-title', $course->name)
@section('page-subtitle', 'Détails du cours')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Emploi du temps</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jour</th>
                                <th>Heure</th>
                                <th>Salle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->schedules as $schedule)
                                <tr>
                                    <td class="font-medium">{{ $schedule->day_of_week }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                    <td>{{ $schedule->classroom ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-gray-500 py-8">Aucun emploi du temps défini</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Quiz</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Questions</th>
                                <th>Points totaux</th>
                                <th>Publié</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->quizzes as $quiz)
                                <tr>
                                    <td class="font-medium">{{ $quiz->title }}</td>
                                    <td>{{ $quiz->questions_count }}</td>
                                    <td>{{ $quiz->total_points }}</td>
                                    <td>
                                        <span class="badge-{{ $quiz->is_published ? 'success' : 'warning' }}">
                                            {{ $quiz->is_published ? 'Publié' : 'Brouillon' }}
                                        </span>
                                    </td>
                                    <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                                    <td><a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-sm text-primary-600">Voir</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">Aucun quiz pour le moment</td></tr>
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
                    <p class="text-sm text-gray-500">Code</p>
                    <p class="font-medium">{{ $course->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">{{ $course->class->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Enseignant</p>
                    <p class="font-medium">{{ $course->teacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Séances</p>
                    <p class="font-medium">{{ $course->sessions_count }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prix</p>
                    <p class="font-medium">{{ $course->price ? number_format($course->price, 2) . ' DA' : 'Gratuit' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Nom arabe</p>
                    <p class="font-medium">{{ $course->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="text-sm text-gray-700">{{ $course->description ?? 'Aucune description' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <span class="badge-{{ $course->is_active ? 'success' : 'danger' }}">{{ $course->is_active ? 'Actif' : 'Inactif' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="text-sm">{{ $course->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn-primary">Modifier le cours</a>
        <a href="{{ route('admin.courses.index') }}" class="btn-outline">Retour aux cours</a>
    </div>
</div>
@endsection
