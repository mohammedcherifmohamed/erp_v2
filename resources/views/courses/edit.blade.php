@extends('layouts.app')

@section('title', 'Modifier le cours')
@section('page-title', 'Modifier le cours')
@section('page-subtitle', $course->name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="class_id" class="label">Section <span class="text-danger-500">*</span></label>
                    <select id="class_id" name="class_id" required class="input @error('class_id') input-error @enderror">
                        <option value="">Sélectionner une section</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $course->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->grade->name ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('class_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $course->name) }}" required class="input @error('name') input-error @enderror">
                        @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="label">Nom arabe</label>
                        <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar', $course->name_ar) }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="code" class="label">Code <span class="text-danger-500">*</span></label>
                        <input id="code" type="text" name="code" value="{{ old('code', $course->code) }}" required class="input @error('code') input-error @enderror">
                        @error('code') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="teacher_id" class="label">Enseignant <span class="text-danger-500">*</span></label>
                        <select id="teacher_id" name="teacher_id" required class="input @error('teacher_id') input-error @enderror">
                            <option value="">Sélectionner un enseignant</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="sessions_count" class="label">Nombre de séances</label>
                        <input id="sessions_count" type="number" name="sessions_count" value="{{ old('sessions_count', $course->sessions_count) }}" class="input" min="0">
                    </div>
                    <div>
                        <label for="price" class="label">Prix (DA)</label>
                        <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" class="input @error('price') input-error @enderror" min="0" placeholder="0.00">
                        @error('price') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input">{{ old('description', $course->description) }}</textarea>
                </div>
                <div>
                    <label for="is_active" class="label">Statut</label>
                    <select id="is_active" name="is_active" class="input">
                        <option value="1" {{ old('is_active', $course->is_active) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $course->is_active) ? '' : 'selected' }}>Inactif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.courses.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour le cours</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
