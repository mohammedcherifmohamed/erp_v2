@extends('layouts.app')

@section('title', 'Edit Attendance')
@section('page-title', 'Edit Attendance')
@section('page-subtitle', $attendance->student->full_name . ' - ' . $attendance->course->name)

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.attendances.update', $attendance) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Student</label>
                        <p class="font-medium text-gray-900">{{ $attendance->student->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="label">Course</label>
                        <p class="font-medium text-gray-900">{{ $attendance->course->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="label">Date</label>
                        <p class="font-medium text-gray-900">{{ $attendance->date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="label">Marked By</label>
                        <p class="font-medium text-gray-900">{{ $attendance->markedBy->full_name ?? '-' }}</p>
                    </div>
                </div>

                <div>
                    <label for="status" class="label">Status <span class="text-danger-500">*</span></label>
                    <select id="status" name="status" required class="input @error('status') input-error @enderror">
                        <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="excused" {{ old('status', $attendance->status) === 'excused' ? 'selected' : '' }}>Excused</option>
                    </select>
                    @error('status') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="label">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="input @error('notes') input-error @enderror" placeholder="Optional notes...">{{ old('notes', $attendance->notes) }}</textarea>
                    @error('notes') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.attendances.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
