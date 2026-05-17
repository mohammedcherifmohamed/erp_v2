@extends('layouts.app')

@section('title', 'Créer un contrat')
@section('page-title', 'Créer un contrat')
@section('page-subtitle', 'Ajouter un nouveau contrat d\'enseignant')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.teacher-contracts.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="teacher_id" class="label">Enseignant <span class="text-danger-500">*</span></label>
                    <select id="teacher_id" name="teacher_id" required class="input @error('teacher_id') input-error @enderror">
                        <option value="">Sélectionner un enseignant</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="contract_type" class="label">Type de contrat <span class="text-danger-500">*</span></label>
                    <select id="contract_type" name="contract_type" required class="input @error('contract_type') input-error @enderror">
                        <option value="">Sélectionner le type</option>
                        <option value="percentage" {{ old('contract_type') === 'percentage' ? 'selected' : '' }}>نسبه مئويه يحددها الادمن</option>
                        <option value="per_session" {{ old('contract_type') === 'per_session' ? 'selected' : '' }}>بالحصه</option>
                        <option value="per_student" {{ old('contract_type') === 'per_student' ? 'selected' : '' }}>بعدد التلاميذ</option>
                        <option value="monthly" {{ old('contract_type') === 'monthly' ? 'selected' : '' }}>شهريا</option>
                    </select>
                    @error('contract_type') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rate" class="label">Taux <span class="text-danger-500">*</span></label>
                    <input id="rate" type="number" step="0.01" name="rate" value="{{ old('rate') }}" required class="input @error('rate') input-error @enderror" placeholder="0.00">
                    @error('rate') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="course_id" class="label">Cours</label>
                        <select id="course_id" name="course_id" class="input">
                            <option value="">Sélectionner un cours (optionnel)</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }} ({{ $course->classe->name ?? '' }} — {{ $course->classe->grade->level->name ?? '' }} {{ $course->classe->grade->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="class_id" class="label">Section</label>
                        <select id="class_id" name="class_id" class="input">
                            <option value="">Sélectionner une section (optionnel)</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->grade->level->name ?? '' }} — {{ $class->grade->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.teacher-contracts.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer le contrat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
