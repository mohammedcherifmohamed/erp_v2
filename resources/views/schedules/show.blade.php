@extends('layouts.app')

@section('title', 'Détails de l\'horaire')
@section('page-title', 'Détails de l\'horaire')
@section('page-subtitle', $schedule->course->name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Informations de l'horaire</h3>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Cours</p>
                    <p class="font-medium">{{ $schedule->course->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">{{ $schedule->course->class->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Enseignant</p>
                    <p class="font-medium">{{ $schedule->course->teacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Salle</p>
                    <p class="font-medium">{{ $schedule->classroom ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Jour</p>
                    <span class="badge-gray inline-block mt-1">{{ $schedule->day_of_week }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <span class="badge-{{ $schedule->is_active ? 'success' : 'danger' }} inline-block mt-1">
                        {{ $schedule->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            <div class="border-t pt-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Heure de début</p>
                        <p class="text-lg font-semibold">{{ $schedule->start_time->format('H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Heure de fin</p>
                        <p class="text-lg font-semibold">{{ $schedule->end_time->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-t flex items-center justify-end gap-3 px-6 py-4">
            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn-primary">Modifier</a>
            <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Retour</a>
        </div>
    </div>
</div>
@endsection
