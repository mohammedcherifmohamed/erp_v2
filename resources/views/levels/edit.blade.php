@extends('layouts.app')

@section('title', 'Edit Level')
@section('page-title', 'Edit Level')
@section('page-subtitle', $level->name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.levels.update', $level) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="label">Name <span class="text-danger-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $level->name) }}" required class="input @error('name') input-error @enderror">
                    @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name_ar" class="label">Arabic Name</label>
                    <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar', $level->name_ar) }}" class="input" dir="rtl">
                </div>
                <div>
                    <label for="code" class="label">Code <span class="text-danger-500">*</span></label>
                    <input id="code" type="text" name="code" value="{{ old('code', $level->code) }}" required class="input @error('code') input-error @enderror">
                    @error('code') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input">{{ old('description', $level->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="sort_order" class="label">Sort Order</label>
                        <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $level->sort_order) }}" class="input w-32" min="0">
                    </div>
                    <div>
                        <label for="is_active" class="label">Status</label>
                        <select id="is_active" name="is_active" class="input">
                            <option value="1" {{ old('is_active', $level->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $level->is_active) ? '' : 'selected' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.levels.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Level</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection