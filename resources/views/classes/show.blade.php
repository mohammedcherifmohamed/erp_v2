@extends('layouts.app')

@section('title', $classe->name)
@section('page-title', $classe->name)
@section('page-subtitle', 'Class details')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Enrolled Students</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classe->enrollments as $enrollment)
                                <tr>
                                    <td class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</td>
                                    <td>{{ $enrollment->student->email ?? '-' }}</td>
                                    <td>
                                        <span class="badge-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.students.show', $enrollment->student) }}" class="text-sm text-primary-600">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-gray-500 py-8">No students enrolled</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Courses</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Code</th>
                                <th>Teacher</th>
                                <th>Sessions</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classe->courses as $course)
                                <tr>
                                    <td class="font-medium">{{ $course->name }}</td>
                                    <td><span class="badge-gray">{{ $course->code }}</span></td>
                                    <td>{{ $course->teacher->full_name ?? '-' }}</td>
                                    <td>{{ $course->sessions_count }}</td>
                                    <td><a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-primary-600">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-gray-500 py-8">No courses for this class</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Schedule</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Time</th>
                                <th>Classroom</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classe->schedules as $schedule)
                                <tr>
                                    <td class="font-medium">{{ $schedule->day_of_week }}</td>
                                    <td>{{ $schedule->course->name ?? '-' }}</td>
                                    <td>{{ $schedule->course->teacher->full_name ?? '-' }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                    <td>{{ $schedule->classroom ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-gray-500 py-8">No schedules set</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Details</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Grade / Level</p>
                    <p class="font-medium">{{ $classe->grade->name ?? '-' }} / {{ $classe->grade->level->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Section</p>
                    <p class="font-medium">{{ $classe->section ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Capacity</p>
                    <p class="font-medium">{{ $classe->capacity }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Enrolled</p>
                    <p class="font-medium">{{ $classe->enrollments_count ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Price</p>
                    <p class="font-medium">{{ number_format($classe->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Homeroom Teacher</p>
                    <p class="font-medium">{{ $classe->homeroomTeacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Public</p>
                    <span class="badge-{{ $classe->is_public ? 'success' : 'gray' }}">{{ $classe->is_public ? 'Yes' : 'No' }}</span>
                </div>
                @if($classe->image)
                    <div>
                        <p class="text-sm text-gray-500">Image</p>
                        <img src="{{ asset('storage/' . $classe->image) }}" class="mt-1 h-24 rounded">
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $classe->is_active ? 'success' : 'danger' }}">{{ $classe->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="text-sm">{{ $classe->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.classes.edit', $classe) }}" class="btn-primary">Edit Class</a>
        <a href="{{ route('admin.classes.index') }}" class="btn-outline">Back to Classes</a>
    </div>
</div>
@endsection
