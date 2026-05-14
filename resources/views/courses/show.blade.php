@extends('layouts.app')

@section('title', $course->name)
@section('page-title', $course->name)
@section('page-subtitle', 'Course details')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Schedule</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Classroom</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->schedules as $schedule)
                                <tr>
                                    <td class="font-medium">{{ $schedule->day_of_week }}</td>
                                    <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                    <td>{{ $schedule->classroom ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-gray-500 py-8">No schedules set</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Quizzes</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Questions</th>
                                <th>Total Points</th>
                                <th>Published</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($course->quizzes as $quiz)
                                <tr>
                                    <td class="font-medium">{{ $quiz->title }}</td>
                                    <td>{{ $quiz->questions_count }}</td>
                                    <td>{{ $quiz->total_points }}</td>
                                    <td>
                                        <span class="badge-{{ $quiz->is_published ? 'success' : 'warning' }}">
                                            {{ $quiz->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td>{{ $quiz->created_at->format('M d, Y') }}</td>
                                    <td><a href="{{ route('teacher.quizzes.show', $quiz) }}" class="text-sm text-primary-600">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">No quizzes yet</td></tr>
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
                    <p class="text-sm text-gray-500">Code</p>
                    <p class="font-medium">{{ $course->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Class</p>
                    <p class="font-medium">{{ $course->class->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Teacher</p>
                    <p class="font-medium">{{ $course->teacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sessions</p>
                    <p class="font-medium">{{ $course->sessions_count }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Credits</p>
                    <p class="font-medium">{{ $course->credits }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Arabic Name</p>
                    <p class="font-medium">{{ $course->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="text-sm text-gray-700">{{ $course->description ?? 'No description' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $course->is_active ? 'success' : 'danger' }}">{{ $course->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="text-sm">{{ $course->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.courses.edit', $course) }}" class="btn-primary">Edit Course</a>
        <a href="{{ route('admin.courses.index') }}" class="btn-outline">Back to Courses</a>
    </div>
</div>
@endsection
