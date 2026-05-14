@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')
@section('page-subtitle', 'Record attendance for a course')

@section('content')
<div class="max-w-3xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.attendances.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="course_id" class="label">Course <span class="text-danger-500">*</span></label>
                        <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }} - {{ $course->class->name ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="date" class="label">Date <span class="text-danger-500">*</span></label>
                        <input id="date" type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="input @error('date') input-error @enderror">
                        @error('date') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-medium text-gray-900">Students</p>
                        <div class="flex gap-2">
                            <button type="button" onclick="setAll('present')" class="btn-sm btn-success">All Present</button>
                            <button type="button" onclick="setAll('absent')" class="btn-sm btn-danger">All Absent</button>
                        </div>
                    </div>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Excused</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td class="font-medium">{{ $student->full_name }}</td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="present" checked class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="radio-status">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}]" value="excused" class="radio-status">
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-4">No students in this class</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @error('attendance') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.attendances.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Save Attendance</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setAll(status) {
        document.querySelectorAll('.radio-status').forEach(function(radio) {
            if (radio.value === status) {
                radio.checked = true;
            }
        });
    }
</script>
@endpush
@endsection
