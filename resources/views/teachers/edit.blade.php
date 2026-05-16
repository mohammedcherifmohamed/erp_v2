@extends('layouts.app')

@section('title', 'Modifier ' . $teacher->full_name)
@section('page-title', 'Modifier l\'enseignant')
@section('page-subtitle', $teacher->full_name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">Prénom <span class="text-danger-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $teacher->first_name) }}" required class="input @error('first_name') input-error @enderror">
                        @error('first_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $teacher->last_name) }}" required class="input @error('last_name') input-error @enderror">
                        @error('last_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="label">Email <span class="text-danger-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $teacher->email) }}" required class="input @error('email') input-error @enderror">
                        @error('email') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="label">Téléphone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="arabic_name" class="label">Nom arabe</label>
                        <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name', $teacher->arabic_name) }}" class="input" dir="rtl">
                    </div>
                    <div>
                        <label for="gender" class="label">Genre</label>
                        <select id="gender" name="gender" class="input">
                            <option value="">Sélectionner le genre</option>
                            <option value="male" {{ old('gender', $teacher->gender) === 'male' ? 'selected' : '' }}>Masculin</option>
                            <option value="female" {{ old('gender', $teacher->gender) === 'female' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_of_birth" class="label">Date de naissance</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', $teacher->date_of_birth ? $teacher->date_of_birth->format('Y-m-d') : '') }}" class="input">
                    </div>
                    <div>
                        <label for="nationality" class="label">Nationalité</label>
                        <input id="nationality" type="text" name="nationality" value="{{ old('nationality', $teacher->nationality) }}" class="input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="id_card_number" class="label">N° CIN</label>
                        <input id="id_card_number" type="text" name="id_card_number" value="{{ old('id_card_number', $teacher->id_card_number) }}" class="input">
                    </div>
                    <div>
                        <label for="specialization" class="label">Spécialisation</label>
                        <input id="specialization" type="text" name="specialization" value="{{ old('specialization', $teacher->specialization) }}" class="input" placeholder="Ex. : Mathématiques">
                    </div>
                </div>
                <div>
                    <label for="bio" class="label">Biographie</label>
                    <textarea id="bio" name="bio" rows="3" class="input @error('bio') input-error @enderror" placeholder="Brève biographie">{{ old('bio', $teacher->bio) }}</textarea>
                    @error('bio') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Actif</span>
                    </label>
                </div>
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Changer le mot de passe</h4>
                    <div class="max-w-xs">
                        <input type="text" name="password" placeholder="Nouveau mot de passe (min. 8 car.)" class="input w-full">
                        <p class="text-xs text-gray-400 mt-1">Laissez vide pour conserver le mot de passe actuel.</p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.teachers.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour l'enseignant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
