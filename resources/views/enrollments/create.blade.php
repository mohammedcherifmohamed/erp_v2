@extends('layouts.app')

@section('title', 'Créer une inscription')
@section('page-title', 'Créer une inscription')
@section('page-subtitle', 'Inscrire un étudiant dans une section')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.enrollments.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="student_id" class="label">Étudiant <span class="text-danger-500">*</span></label>
                    <select id="student_id" name="student_id" required class="input @error('student_id') input-error @enderror">
                        <option value="">Sélectionner un étudiant</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->full_name }} ({{ $student->email }})</option>
                        @endforeach
                    </select>
                    @error('student_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="class_id" class="label">Section <span class="text-danger-500">*</span></label>
                    <select id="class_id" name="class_id" required class="input @error('class_id') input-error @enderror">
                        <option value="">Sélectionner une section</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->grade->name ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('class_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.enrollments.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer l'inscription</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
