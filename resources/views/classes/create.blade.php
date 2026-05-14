@extends('layouts.app')

@section('title', 'Create Class')
@section('page-title', 'Create Class')
@section('page-subtitle', 'Add a new academic class')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="grade_id" class="label">Grade <span class="text-danger-500">*</span></label>
                    <select id="grade_id" name="grade_id" required class="input @error('grade_id') input-error @enderror">
                        <option value="">Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ old('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }} ({{ $grade->level->name ?? '' }})</option>
                        @endforeach
                    </select>
                    @error('grade_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="label">Name <span class="text-danger-500">*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror" placeholder="e.g., Class A">
                        @error('name') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="name_ar" class="label">Arabic Name</label>
                        <input id="name_ar" type="text" name="name_ar" value="{{ old('name_ar') }}" class="input" placeholder="الفصل أ" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="section" class="label">Section</label>
                        <input id="section" type="text" name="section" value="{{ old('section') }}" class="input" placeholder="e.g., A">
                    </div>
                    <div>
                        <label for="capacity" class="label">Capacity <span class="text-danger-500">*</span></label>
                        <input id="capacity" type="number" name="capacity" value="{{ old('capacity', 30) }}" required class="input @error('capacity') input-error @enderror" min="1">
                        @error('capacity') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="price" class="label">Price</label>
                        <input id="price" type="number" step="0.01" name="price" value="{{ old('price', 0) }}" class="input">
                    </div>
                </div>
                <div>
                    <label for="homeroom_teacher_id" class="label">Homeroom Teacher</label>
                    <select id="homeroom_teacher_id" name="homeroom_teacher_id" class="input @error('homeroom_teacher_id') input-error @enderror">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('homeroom_teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                    @error('homeroom_teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input @error('description') input-error @enderror" placeholder="Brief description">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Public class (visible during enrollment)</span>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.classes.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Create Class</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
