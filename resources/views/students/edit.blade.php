@extends('layouts.app')

@section('title', 'Modifier ' . $student->full_name)
@section('page-title', 'Modifier l\'étudiant')
@section('page-subtitle', $student->full_name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="label">Prénom <span class="text-danger-500">*</span></label>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $student->first_name) }}" required class="input @error('first_name') input-error @enderror">
                        @error('first_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="label">Nom <span class="text-danger-500">*</span></label>
                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $student->last_name) }}" required class="input @error('last_name') input-error @enderror">
                        @error('last_name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="label">Email <span class="text-danger-500">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $student->email) }}" required class="input @error('email') input-error @enderror">
                        @error('email') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="label">Téléphone</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="arabic_name" class="label">Nom arabe</label>
                        <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name', $student->arabic_name) }}" class="input" dir="rtl">
                    </div>
                    <div>
                        <label for="date_of_birth" class="label">Date de naissance</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}" class="input">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="gender" class="label">Genre</label>
                        <select id="gender" name="gender" class="input">
                            <option value="">Sélectionner le genre</option>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Masculin</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    <div>
                        <label for="blood_type" class="label">Groupe sanguin</label>
                        <select id="blood_type" name="blood_type" class="input">
                            <option value="">Sélectionner</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                                <option value="{{ $type }}" {{ old('blood_type', $student->blood_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="address" class="label">Adresse</label>
                    <textarea id="address" name="address" rows="2" class="input">{{ old('address', $student->address) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="emergency_contact" class="label">Contact d'urgence</label>
                        <input id="emergency_contact" type="text" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact) }}" class="input">
                    </div>
                    <div>
                        <label for="allergies" class="label">Allergies / Notes médicales</label>
                        <input id="allergies" type="text" name="allergies" value="{{ old('allergies', $student->allergies) }}" class="input">
                    </div>
                </div>
                <div>
                    <label for="parent_id" class="label">Parent / Tuteur</label>
                    <select id="parent_id" name="parent_id" class="input @error('parent_id') input-error @enderror">
                        <option value="">Sélectionner un parent</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id', $student->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->full_name }} ({{ $parent->email }})</option>
                        @endforeach
                    </select>
                    @error('parent_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Actif</span>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.students.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Mettre à jour l'étudiant</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
