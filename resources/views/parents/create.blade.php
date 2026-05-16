@extends('layouts.app')

@section('title', 'Créer un parent')
@section('page-title', 'Créer un parent')
@section('page-subtitle', 'Inscrire un nouveau parent/tuteur')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.parents.store') }}" class="space-y-6">
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
                        <label for="secondary_phone" class="label">Téléphone secondaire</label>
                        <input id="secondary_phone" type="text" name="secondary_phone" value="{{ old('secondary_phone') }}" class="input">
                    </div>
                </div>
                <div>
                    <label for="arabic_name" class="label">Nom arabe</label>
                    <input id="arabic_name" type="text" name="arabic_name" value="{{ old('arabic_name') }}" class="input" dir="rtl">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="profession" class="label">Profession</label>
                        <input id="profession" type="text" name="profession" value="{{ old('profession') }}" class="input">
                    </div>
                    <div>
                        <label for="relationship" class="label">Relation</label>
                        <select id="relationship" name="relationship" class="input">
                            <option value="">Sélectionner</option>
                            <option value="father" {{ old('relationship') === 'father' ? 'selected' : '' }}>Père</option>
                            <option value="mother" {{ old('relationship') === 'mother' ? 'selected' : '' }}>Mère</option>
                            <option value="guardian" {{ old('relationship') === 'guardian' ? 'selected' : '' }}>Tuteur</option>
                            <option value="other" {{ old('relationship') === 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.parents.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer le parent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
