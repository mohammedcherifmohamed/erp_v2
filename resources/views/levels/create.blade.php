@extends('layouts.app')

@section('title', 'Créer un niveau')
@section('page-title', 'Créer un niveau')
@section('page-subtitle', 'Ajouter un nouveau cycle académique')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.levels.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="label">Nom <span class="text-danger-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror" placeholder="Ex. : Primaire">
                    @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name_ar" class="label">Nom arabe</label>
                    <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar') }}" class="input" placeholder="الاسم بالعربية" dir="rtl">
                </div>
                <div>
                    <label for="code" class="label">Code <span class="text-danger-500">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" required class="input @error('code') input-error @enderror" placeholder="Ex. : PRI">
                    @error('code') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input @error('description') input-error @enderror" placeholder="Brève description">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="sort_order" class="label">Ordre</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="input w-32" min="0">
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.levels.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer le niveau</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection