@extends('layouts.app')

@section('title', 'Modifier la classe')
@section('page-title', 'Modifier la classe')
@section('page-subtitle', $grade->name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.grades.update', $grade) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="level_id" class="label">Niveau <span class="text-danger-500">*</span></label>
                    <select id="level_id" name="level_id" required class="input @error('level_id') input-error @enderror">
                        <option value="">Sélectionner un niveau</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id', $grade->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                    @error('level_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name', $grade->name) }}" required class="input @error('name') input-error @enderror">
                        @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="label">Nom arabe</label>
                        <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar', $grade->name_ar) }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="code" class="label">Code <span class="text-danger-500">*</span></label>
                        <input id="code" type="text" name="code" value="{{ old('code', $grade->code) }}" required class="input @error('code') input-error @enderror">
                        @error('code') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="sort_order" class="label">Ordre</label>
                        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $grade->sort_order) }}" class="input w-32" min="0">
                    </div>
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input">{{ old('description', $grade->description) }}</textarea>
                </div>
                <div>
                    <label for="is_active" class="label">Statut</label>
                    <select id="is_active" name="is_active" class="input">
                        <option value="1" {{ old('is_active', $grade->is_active) ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $grade->is_active) ? '' : 'selected' }}>Inactif</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.grades.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour la classe</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
