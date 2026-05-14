@extends('layouts.app')

@section('title', 'Create Level')
@section('page-title', 'Create Level')
@section('page-subtitle', 'Add a new academic level')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.levels.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="label">Name <span class="text-danger-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror" placeholder="e.g., Primary School">
                    @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name_ar" class="label">Arabic Name</label>
                    <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar') }}" class="input" placeholder="الاسم بالعربية" dir="rtl">
                </div>
                <div>
                    <label for="code" class="label">Code <span class="text-danger-500">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" required class="input @error('code') input-error @enderror" placeholder="e.g., PRI">
                    @error('code') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input @error('description') input-error @enderror" placeholder="Brief description">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="sort_order" class="label">Sort Order</label>
                    <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="input w-32" min="0">
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.levels.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Create Level</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection