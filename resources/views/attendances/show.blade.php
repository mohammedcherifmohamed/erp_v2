@extends('layouts.app')

@section('title', 'Détails de la présence')
@section('page-title', 'Détails de la présence')
@section('page-subtitle', $attendance->date->format('M d, Y'))

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Enregistrement de présence</h3>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Étudiant</p>
                    <p class="font-medium">{{ $attendance->student->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Course</p>
                    <p class="font-medium">{{ $attendance->course->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-medium">{{ $attendance->date->format('l, F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Statut</p>
                    <span class="badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : ($attendance->status === 'late' ? 'warning' : 'gray')) }} inline-block mt-1">
                        {{ ucfirst($attendance->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Marqué par</p>
                    <p class="font-medium">{{ $attendance->markedBy->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Créé le</p>
                    <p class="font-medium">{{ $attendance->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            @if($attendance->notes)
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="text-sm text-gray-700 mt-1">{{ $attendance->notes }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer border-t flex items-center justify-end gap-3 px-6 py-4">
            <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn-primary">Modifier</a>
            <a href="{{ route('admin.attendances.index') }}" class="btn-outline">Retour</a>
        </div>
    </div>
</div>
@endsection
