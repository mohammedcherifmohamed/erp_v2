@extends('layouts.app')

@section('title', 'Créer un étudiant')
@section('page-title', 'Créer un étudiant')
@section('page-subtitle', 'Inscrire un nouvel étudiant')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">Prénom <span class="text-danger-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required class="input @error('first_name') input-error @enderror">
                        @error('first_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required class="input @error('last_name') input-error @enderror">
                        @error('last_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="label">Email <span class="text-danger-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input @error('email') input-error @enderror">
                        @error('email') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="label">Mot de passe <span class="text-danger-500">*</span></label>
                        <input id="password" type="password" name="password" required class="input @error('password') input-error @enderror">
                        @error('password') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="label">Téléphone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="input">
                    </div>
                    <div>
                        <label for="arabic_name" class="label">Nom arabe</label>
                        <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name') }}" class="input" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="date_of_birth" class="label">Date de naissance</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="input">
                    </div>
                    <div>
                        <label for="gender" class="label">Genre</label>
                        <select id="gender" name="gender" class="input">
                            <option value="">Sélectionner le genre</option>
                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Masculin</option>
                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="address" class="label">Adresse</label>
                    <textarea id="address" name="address" rows="2" class="input">{{ old('address') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="emergency_contact" class="label">Contact d'urgence</label>
                        <input id="emergency_contact" type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="input">
                    </div>
                    <div>
                        <label for="blood_type" class="label">Groupe sanguin</label>
                        <select id="blood_type" name="blood_type" class="input">
                            <option value="">Sélectionner</option>
                            <option value="A+" {{ old('blood_type') === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type') === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type') === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type') === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type') === 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type') === 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type') === 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type') === 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="allergies" class="label">Allergies / Notes médicales</label>
                    <textarea id="allergies" name="allergies" rows="2" class="input">{{ old('allergies') }}</textarea>
                </div>
                <div>
                    <label for="parent_id" class="label">Parent / Tuteur</label>
                    <select id="parent_id" name="parent_id" class="input @error('parent_id') input-error @enderror">
                        <option value="">Sélectionner un parent</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->full_name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.students.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer l'étudiant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
