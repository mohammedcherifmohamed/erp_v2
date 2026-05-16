@extends('layouts.app')

@section('title', "Détails de l'inscription")
@section('page-title', "Détails de l'inscription")
@section('page-subtitle', $enrollment->student->full_name ?? '')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Informations d'inscription</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Étudiant</p>
                        <p class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type d'inscription</p>
                        <p class="font-medium">
                            @if($enrollment->course_id)
                                <span class="badge-primary">Cours individuel</span>
                            @else
                                <span class="badge-success">Forfait complet</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">
                            @if($enrollment->course_id) Cours @else Section @endif
                        </p>
                        <p class="font-medium">
                            @if($enrollment->course_id)
                                {{ $enrollment->course->name ?? '-' }}
                            @else
                                {{ $enrollment->classe->name ?? '-' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Niveau / Cycle</p>
                        <p class="font-medium">{{ $enrollment->classe->grade->name ?? '-' }} / {{ $enrollment->classe->grade->level->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Statut</p>
                        <span class="badge-{{ $enrollment->status === 'approved' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : ($enrollment->status === 'rejected' ? 'danger' : 'gray')) }}">
                            @switch($enrollment->status)
                                @case('approved') Approuvé @break
                                @case('pending') En attente @break
                                @case('rejected') Refusé @break
                                @default {{ ucfirst($enrollment->status) }}
                            @endswitch
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date de demande</p>
                        <p class="font-medium">{{ $enrollment->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approuvé par</p>
                        <p class="font-medium">{{ $enrollment->approver->full_name ?? 'En attente' }}</p>
                    </div>
                    @if($enrollment->start_date)
                    <div>
                        <p class="text-sm text-gray-500">Début de période</p>
                        <p class="font-medium">{{ $enrollment->start_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    @if($enrollment->end_date)
                    <div>
                        <p class="text-sm text-gray-500">Fin de période</p>
                        <p class="font-medium">{{ $enrollment->end_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
                @if($enrollment->rejection_reason)
                    <div>
                        <p class="text-sm text-gray-500">Motif du refus</p>
                        <p class="text-sm text-danger-600">{{ $enrollment->rejection_reason }}</p>
                    </div>
                @endif
            </div>

            @if($enrollment->status === 'pending')
                <div class="card-footer border-t p-6 space-y-4">
                    <h4 class="font-semibold text-gray-900">Approuver l'inscription</h4>
                    <form method="POST" action="{{ route('admin.enrollments.approve', $enrollment) }}" class="space-y-4">
                        @method('PATCH')
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="label">Date de début <span class="text-danger-500">*</span></label>
                                <input id="start_date" type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required class="input @error('start_date') input-error @enderror">
                                @error('start_date') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="end_date" class="label">Date de fin <span class="text-danger-500">*</span></label>
                                <input id="end_date" type="date" name="end_date" value="{{ old('end_date', now()->addYear()->format('Y-m-d')) }}" required class="input @error('end_date') input-error @enderror">
                                @error('end_date') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="btn-success">✅ Approuver l'inscription</button>
                            <button type="button" onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="btn-danger">❌ Refuser</button>
                        </div>
                    </form>

                    <div id="reject-form" class="hidden border-t pt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Refuser l'inscription</h4>
                        <form method="POST" action="{{ route('admin.enrollments.reject', $enrollment) }}">
                            @csrf
                            <div>
                                <label for="rejection_reason" class="label">Motif du refus <span class="text-danger-500">*</span></label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="input @error('rejection_reason') input-error @enderror" placeholder="Expliquez le motif du refus..." required>{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button type="submit" class="btn-danger mt-3" onclick="return confirm('Confirmer le refus de cette inscription ?')">Refuser définitivement</button>
                        </form>
                    </div>
                </div>
            @endif

            @if($enrollment->status === 'rejected')
                <div class="card-footer border-t px-6 py-4">
                    <a href="{{ route('admin.enrollments.approve', $enrollment) }}" class="btn-success">Ré-approuver</a>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Profil étudiant</h3>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-sm">{{ $enrollment->student->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Téléphone</p>
                    <p class="text-sm">{{ $enrollment->student->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Parent</p>
                    <p class="text-sm">{{ $enrollment->student->studentProfile->parent->full_name ?? 'Aucun parent lié' }}</p>
                </div>
                <a href="{{ route('admin.students.show', $enrollment->student) }}" class="btn-outline w-full text-center text-sm">Voir le profil</a>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.enrollments.index') }}" class="btn-outline">← Retour aux inscriptions</a>
        <a href="{{ route('admin.enrollments.pending') }}" class="btn-secondary">Demandes en attente</a>
    </div>
</div>
@endsection