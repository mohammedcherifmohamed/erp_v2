@extends('layouts.app')

@section('title', 'Modifier la section')
@section('page-title', 'Modifier la section')
@section('page-subtitle', $classe->name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.update', $classe) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="grade_id" class="label">Classe <span class="text-danger-500">*</span></label>
                    <select id="grade_id" name="grade_id" required class="input @error('grade_id') input-error @enderror">
                        <option value="">Sélectionner une classe</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('grade_id', $classe->grade_id) == $grade->id ? 'selected' : '' }}>{{ $grade->name }} ({{ $grade->level->name ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('grade_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $classe->name) }}" required class="input @error('name') input-error @enderror">
                        @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="label">Nom arabe</label>
                        <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar', $classe->name_ar) }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="section" class="label">Section</label>
                        <input id="section" type="text" name="section" value="{{ old('section', $classe->section) }}" class="input">
                    </div>
                    <div>
                        <label for="capacity" class="label">Capacité <span class="text-danger-500">*</span></label>
                        <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $classe->capacity) }}" required class="input @error('capacity') input-error @enderror" min="1">
                        @error('capacity') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="price" class="label">Prix</label>
                        <input id="price" type="number" step="0.01" name="price" value="{{ old('price', $classe->price) }}" class="input">
                    </div>
                </div>
                <div>
                    <label for="homeroom_teacher_id" class="label">Professeur principal</label>
                    <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="input @error('homeroom_teacher_id') input-error @enderror">
                        <option value="">Sélectionner un enseignant</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id', $classe->homeroom_teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input">{{ old('description', $classe->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="image" class="label">Image de la section</label>
                        <input id="image" type="file" name="image" accept="image/*" class="input @error('image') input-error @enderror">
                        @error('image') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                        @if($classe->image)
                            <img src="{{ asset('storage/' . $classe->image) }}" class="mt-2 h-20 rounded">
                        @endif
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public', $classe->is_public) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                            <span class="text-sm text-gray-700">Section publique</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="is_active" class="label">Statut</label>
                    <select id="is_active" name="is_active" class="input">
                        <option value="1" {{ old('is_active', $classe->is_active) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $classe->is_active) ? '' : 'selected' }}>Inactif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.classes.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour la section</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
