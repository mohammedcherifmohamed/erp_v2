@extends('layouts.app')

@section('title', 'Edit Schedule')
@section('page-title', 'Edit Schedule')
@section('page-subtitle', $schedule->course->name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label for="course_id" class="label">Course <span class="text-danger-500">*</span></label>
                    <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $schedule->course_id) == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} - {{ $course->class->name ?? '' }} ({{ $course->teacher->full_name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="classroom" class="label">Classroom</label>
                    <input id="classroom" type="text" name="classroom" value="{{ old('classroom', $schedule->classroom) }}" class="input">
                </div>
                <div>
                    <label for="day_of_week" class="label">Day of Week <span class="text-danger-500">*</span></label>
                    <select id="day_of_week" name="day_of_week" required class="input @error('day_of_week') input-error @enderror">
                        <option value="">Select Day</option>
                        @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $schedule->day_of_week) === $day ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="label">Start Time <span class="text-danger-500">*</span></label>
                        <input id="start_time" type="time" name="start_time" value="{{ old('start_time', $schedule->start_time->format('H:i')) }}" required class="input @error('start_time') input-error @enderror">
                        @error('start_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="label">End Time <span class="text-danger-500">*</span></label>
                        <input id="end_time" type="time" name="end_time" value="{{ old('end_time', $schedule->end_time->format('H:i')) }}" required class="input @error('end_time') input-error @enderror">
                        @error('end_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="is_active" class="label">Status</label>
                    <select id="is_active" name="is_active" class="input">
                        <option value="1" {{ old('is_active', $schedule->is_active) ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $schedule->is_active) ? '' : 'selected' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
