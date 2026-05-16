@extends('layouts.app')

@section('title', 'Créer un horaire')
@section('page-title', 'Créer un horaire')
@section('page-subtitle', 'Ajouter un nouvel horaire')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="course_id" class="label">Cours <span class="text-danger-500">*</span></label>
                    <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                        <option value="">Sélectionner un cours</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}
                                data-class-id="{{ $course->classe->id ?? '' }}"
                                data-class-name="{{ $course->classe->name ?? '' }}"
                                data-teacher-id="{{ $course->teacher_id }}"
                                data-teacher-name="{{ $course->teacher->full_name }}">
                                {{ $course->name }} - {{ $course->classe?->name ?? '' }} ({{ $course->teacher->full_name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Hidden fields populated by JS --}}
                <input type="hidden" name="class_id" id="class_id" value="{{ old('class_id') }}">
                <input type="hidden" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Section</p>
                        <p id="selected-class" class="font-medium text-gray-700">-</p>
                        @error('class_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Enseignant</p>
                        <p id="selected-teacher" class="font-medium text-gray-700">-</p>
                        @error('teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="classroom" class="label">Salle</label>
                    <input id="classroom" type="text" name="classroom" value="{{ old('classroom') }}" class="input" placeholder="Ex. : Salle 101">
                </div>

                <div>
                    <label for="day_of_week" class="label">Jour de la semaine <span class="text-danger-500">*</span></label>
                    <select id="day_of_week" name="day_of_week" required class="input @error('day_of_week') input-error @enderror">
                        <option value="">Sélectionner un jour</option>
                        <option value="monday" {{ old('day_of_week') === 'monday' ? 'selected' : '' }}>Lundi</option>
                        <option value="tuesday" {{ old('day_of_week') === 'tuesday' ? 'selected' : '' }}>Mardi</option>
                        <option value="wednesday" {{ old('day_of_week') === 'wednesday' ? 'selected' : '' }}>Mercredi</option>
                        <option value="thursday" {{ old('day_of_week') === 'thursday' ? 'selected' : '' }}>Jeudi</option>
                        <option value="friday" {{ old('day_of_week') === 'friday' ? 'selected' : '' }}>Vendredi</option>
                        <option value="saturday" {{ old('day_of_week') === 'saturday' ? 'selected' : '' }}>Samedi</option>
                        <option value="sunday" {{ old('day_of_week') === 'sunday' ? 'selected' : '' }}>Dimanche</option>
                    </select>
                    @error('day_of_week') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="label">Heure de début <span class="text-danger-500">*</span></label>
                        <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required class="input @error('start_time') input-error @enderror">
                        @error('start_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="label">Heure de fin <span class="text-danger-500">*</span></label>
                        <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required class="input @error('end_time') input-error @enderror">
                        @error('end_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer l'horaire</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('course_id').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const classId = option.dataset.classId;
    const className = option.dataset.className;
    const teacherId = option.dataset.teacherId;
    const teacherName = option.dataset.teacherName;

    document.getElementById('class_id').value = classId || '';
    document.getElementById('teacher_id').value = teacherId || '';
    document.getElementById('selected-class').textContent = className || '-';
    document.getElementById('selected-teacher').textContent = teacherName || '-';
});
</script>
@endsection