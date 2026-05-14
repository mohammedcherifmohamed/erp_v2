@extends('layouts.app')

@section('title', 'Edit Contract')
@section('page-title', 'Edit Contract')
@section('page-subtitle', $contract->teacher->full_name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.teacher-contracts.update', $contract) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="teacher_id" class="label">Teacher <span class="text-danger-500">*</span></label>
                    <select id="teacher_id" name="teacher_id" required class="input @error('teacher_id') input-error @enderror">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $contract->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="contract_type" class="label">Contract Type <span class="text-danger-500">*</span></label>
                    <select id="contract_type" name="contract_type" required class="input @error('contract_type') input-error @enderror">
                        <option value="">Select Type</option>
                        <option value="hourly" {{ old('contract_type', $contract->contract_type) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                        <option value="monthly" {{ old('contract_type', $contract->contract_type) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="per_course" {{ old('contract_type', $contract->contract_type) === 'per_course' ? 'selected' : '' }}>Per Course</option>
                        <option value="per_student" {{ old('contract_type', $contract->contract_type) === 'per_student' ? 'selected' : '' }}>Per Student</option>
                    </select>
                    @error('contract_type') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rate" class="label">Rate <span class="text-danger-500">*</span></label>
                    <input id="rate" type="number" step="0.01" name="rate" value="{{ old('rate', $contract->rate) }}" required class="input @error('rate') input-error @enderror" placeholder="0.00">
                    @error('rate') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="course_id" class="label">Course</label>
                        <select id="course_id" name="course_id" class="input">
                            <option value="">Select Course (optional)</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $contract->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }} ({{ $course->class->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="class_id" class="label">Class</label>
                        <select id="class_id" name="class_id" class="input">
                            <option value="">Select Class (optional)</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $contract->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }} ({{ $class->grade->name ?? '' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contract->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <span class="text-sm text-gray-700">Active</span>
                    </label>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.teacher-contracts.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Contract</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
