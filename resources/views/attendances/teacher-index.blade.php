@extends('layouts.app')

@section('title', 'My Courses - Attendance')
@section('page-title', 'Attendance')
@section('page-subtitle', 'Manage attendance for your courses')

@section('content')
<div class="space-y-6">
    @forelse($courses as $course)
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $course->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $course->code }} - {{ $course->class->name ?? '' }} ({{ $course->sessions_count }} sessions)</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('teacher.attendances.mark', $course) }}" class="btn-sm btn-primary">Mark Attendance</a>
                    <a href="{{ route('teacher.attendances.history', $course) }}" class="btn-sm btn-outline">History</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-gray-500 py-8">
                No courses assigned to you
            </div>
        </div>
    @endforelse
</div>
@endsection
