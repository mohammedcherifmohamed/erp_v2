@extends('layouts.app')

@section('title', 'Tableau de bord Enseignant')
@section('page-title', 'Tableau de bord Enseignant')
@section('page-subtitle', 'Gérez vos cours et vos classes')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Mes Cours</p>
                    <p class="stat-value text-primary-600">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Emplois du temps</p>
                    <p class="stat-value text-success-600">{{ $stats['total_schedules'] }}</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Quiz</p>
                    <p class="stat-value text-warning-600">{{ $stats['total_quizzes'] }}</p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Solde du portefeuille</p>
                    <p class="stat-value text-success-600">{{ number_format($stats['wallet_balance'], 2) }} MAD</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Emploi du temps d'aujourd'hui</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-100">
                    @forelse($todaySchedule as $schedule)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $schedule->course->name }}</p>
                                <p class="text-sm text-gray-500">{{ $schedule->classe->name }} &middot; {{ $schedule->classroom ?? 'Aucune salle' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Aucun cours prévu pour aujourd'hui</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Mes Cours</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $course->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $course->classe?->grade?->level?->name ?? '' }} > {{ $course->classe?->name ?? '' }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('teacher.attendances.mark', $course) }}" class="btn-sm btn-outline">Présences</a>
                                    <a href="{{ route('teacher.quizzes.create', ['course_id' => $course->id]) }}" class="btn-sm btn-primary">Quiz</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">Aucun cours attribué pour le moment</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection