@extends('layouts.app')

@section('title', 'Create Schedule')
@section('page-title', 'Create Schedule')
@section('page-subtitle', 'Add a new schedule entry')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.schedules.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="course_id" class="label">Course <span class="text-danger-500">*</span></label>
                    <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }} data-class="{{ $course->class?->name ?? 'N/A' }}" data-teacher="{{ $course->teacher->full_name }}">
                                {{ $course->name }} - {{ $course->class->name ?? '' }} ({{ $course->teacher->full_name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p id="selected-class" class="font-medium text-gray-700">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Teacher</p>
                        <p id="selected-teacher" class="font-medium text-gray-700">-</p>
                    </div>
                </div>
                <div>
                    <label for="classroom" class="label">Classroom</label>
                    <input id="classroom" type="text" name="classroom" value="{{ old('classroom') }}" class="input" placeholder="e.g., Room 101">
                </div>
                <div>
                    <label for="day_of_week" class="label">Day of Week <span class="text-danger-500">*</span></label>
                    <select id="day_of_week" name="day_of_week" required class="input @error('day_of_week') input-error @enderror">
                        <option value="">Select Day</option>
                        <option value="Monday" {{ old('day_of_week') === 'Monday' ? 'selected' : '' }}>Monday</option>
                        <option value="Tuesday" {{ old('day_of_week') === 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                        <option value="Wednesday" {{ old('day_of_week') === 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                        <option value="Thursday" {{ old('day_of_week') === 'Thursday' ? 'selected' : '' }}>Thursday</option>
                        <option value="Friday" {{ old('day_of_week') === 'Friday' ? 'selected' : '' }}>Friday</option>
                        <option value="Saturday" {{ old('day_of_week') === 'Saturday' ? 'selected' : '' }}>Saturday</option>
                        <option value="Sunday" {{ old('day_of_week') === 'Sunday' ? 'selected' : '' }}>Sunday</option>
                    </select>
                    @error('day_of_week') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="label">Start Time <span class="text-danger-500">*</span></label>
                        <input id="start_time" type="time" name="start_time" value="{{ old('start_time') }}" required class="input @error('start_time') input-error @enderror">
                        @error('start_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="label">End Time <span class="text-danger-500">*</span></label>
                        <input id="end_time" type="time" name="end_time" value="{{ old('end_time') }}" required class="input @error('end_time') input-error @enderror">
                        @error('end_time') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Create Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('course_id').addEventListener('change', function() {
        var option = this.options[this.selectedIndex];
        document.getElementById('selected-class').textContent = option.dataset.class || '-';
        document.getElementById('selected-teacher').textContent = option.dataset.teacher || '-';
    });
</script>
@endpush
@endsection
