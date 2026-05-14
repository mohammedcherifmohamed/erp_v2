@extends('layouts.app')

@section('title', 'Attendance Analytics')
@section('page-title', 'Attendance Analytics')
@section('page-subtitle', 'Track attendance statistics')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-4 gap-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-success-600">{{ $presentPercent ?? 0 }}%</p>
                <p class="text-sm text-gray-500 mt-1">Present Rate</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-danger-600">{{ $absentCount ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Absences</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-warning-600">{{ $lateCount ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Late Arrivals</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-gray-600">{{ $excusedCount ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-1">Excused</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Per-Course Breakdown</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Class</th>
                            <th>Total Sessions</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Excused</th>
                            <th>Present %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courseStats as $stat)
                            <tr>
                                <td class="font-medium">{{ $stat->course->name ?? '-' }}</td>
                                <td>{{ $stat->course->class->name ?? '-' }}</td>
                                <td>{{ $stat->total }}</td>
                                <td class="text-success-600 font-medium">{{ $stat->present }}</td>
                                <td class="text-danger-600 font-medium">{{ $stat->absent }}</td>
                                <td class="text-warning-600 font-medium">{{ $stat->late }}</td>
                                <td>{{ $stat->excused }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-success-500 h-2 rounded-full" style="width: {{ $stat->total > 0 ? ($stat->present / $stat->total) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium">{{ $stat->total > 0 ? round(($stat->present / $stat->total) * 100) : 0 }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-gray-500 py-8">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
