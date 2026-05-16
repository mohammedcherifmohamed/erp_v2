@extends('layouts.app')

@section('title', 'Modifier l\'horaire')
@section('page-title', 'Modifier l\'horaire')
@section('page-subtitle', $schedule->course->name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="course_id" class="label">Cours <span class="text-danger-500">*</span></label>
                    <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                        <option value="">Sélectionner un cours</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} - {{ $course->class->name ?? '' }} ({{ $course->teacher->full_name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="classroom" class="label">Salle</label>
                    <input id="classroom" type="text" name="classroom" value="{{ old('classroom', $schedule->classroom) }}" class="input">
                </div>
                <div>
                    <label for="day_of_week" class="label">Jour de la semaine <span class="text-danger-500">*</span></label>
                    <select id="day_of_week" name="day_of_week" required class="input @error('day_of_week') input-error @enderror">
                        <option value="">Sélectionner un jour</option>
                        @foreach(['monday'=>'Lundi','tuesday'=>'Mardi','wednesday'=>'Mercredi','thursday'=>'Jeudi','friday'=>'Vendredi','saturday'=>'Samedi','sunday'=>'Dimanche'] as $val => $label)
                            <option value="{{ $val }}" {{ old('day_of_week', $schedule->day_of_week) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="label">Heure de début <span class="text-danger-500">*</span></label>
                        <input id="start_time" type="time" name="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required class="input @error('start_time') input-error @enderror">
                        @error('start_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="label">Heure de fin <span class="text-danger-500">*</span></label>
                        <input id="end_time" type="time" name="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required class="input @error('end_time') input-error @enderror">
                        @error('end_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="is_active" class="label">Statut</label>
                    <select id="is_active" name="is_active" class="input">
                        <option value="1" {{ old('is_active', $schedule->is_active) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $schedule->is_active) ? '' : 'selected' }}>Inactif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour l'horaire</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
