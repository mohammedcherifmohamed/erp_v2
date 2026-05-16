@extends('layouts.app')

@section('title', 'Créer une annonce')
@section('page-title', 'Créer une annonce')
@section('page-subtitle', 'Publier une nouvelle annonce')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.announcements.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="title" class="label">Titre <span class="text-danger-500">*</span></label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required class="input @error('title') input-error @enderror" placeholder="Titre de l'annonce">
                    @error('title') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="content" class="label">Contenu <span class="text-danger-500">*</span></label>
                    <textarea id="content" name="content" rows="6" required class="input @error('content') input-error @enderror" placeholder="Écrivez votre annonce ici...">{{ old('content') }}</textarea>
                    @error('content') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="class_id" class="label">Section cible</label>
                    <select id="class_id" name="class_id" class="input @error('class_id') input-error @enderror">
                        <option value="">Toutes les sections (Global)</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->grade->name ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('class_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_global" value="1" {{ old('is_global') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Global (visible pour tous)</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Publier immédiatement</span>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('teacher.announcements.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer l'annonce</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
