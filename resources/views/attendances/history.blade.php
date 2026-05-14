@extends('layouts.app')

@section('title', 'Attendance History - ' . $course->name)
@section('page-title', 'Attendance History')
@section('page-subtitle', $course->name . ' (' . ($course->classe?->name ?? '') . ')')

@section('content')
<div class="card">
    <div class="card-header flex items-center justify-between">
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="from" value="{{ request('from') }}" class="input w-40" placeholder="From">
            <input type="date" name="to" value="{{ request('to') }}" class="input w-40" placeholder="To">
            <select name="status" class="input w-36">
                <option value="">All Status</option>
                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                <option value="excused" {{ request('status') === 'excused' ? 'selected' : '' }}>Excused</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
            <a href="{{ route('admin.attendances.index') }}" class="btn-outline">Reset</a>
        </form>
        <a href="{{ route('admin.attendances.create') }}" class="btn-primary">Mark Attendance</a>
    </div>
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Marked By</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                            <td class="font-medium">{{ $attendance->student->full_name ?? '-' }}</td>
                            <td>
                                <span class="badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : ($attendance->status === 'late' ? 'warning' : 'gray')) }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="text-sm text-gray-500">{{ $attendance->notes ?? '-' }}</td>
                            <td>{{ $attendance->markedBy->full_name ?? '-' }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.attendances.show', $attendance) }}" class="btn-sm btn-outline">View</a>
                                    <a href="{{ route('admin.attendances.edit', $attendance) }}" class="btn-sm btn-secondary">Edit</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No attendance records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($attendances->hasPages())
        <div class="card-footer border-t px-6 py-4">
            {{ $attendances->links() }}
        </div>
    @endif
</div>
@endsection
