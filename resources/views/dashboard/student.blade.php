@extends('layouts.app')

@section('title', 'Student Dashboard')
@section('page-title', 'Student Dashboard')
@section('page-subtitle', 'Your learning overview')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Enrollments</p>
                    <p class="stat-value text-primary-600">{{ $stats['total_enrollments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Courses</p>
                    <p class="stat-value text-success-600">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="w-12 h-12 bg-success-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Sessions This Week</p>
                    <p class="stat-value text-warning-600">{{ $stats['upcoming_schedules'] }}</p>
                </div>
                <div class="w-12 h-12 bg-warning-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">Pending Invoices</p>
                    <p class="stat-value text-danger-600">{{ $stats['pending_invoices'] }}</p>
                </div>
                <div class="w-12 h-12 bg-danger-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-danger-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h3v6m6-6a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Today's Schedule</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-100">
                    @forelse($todaySchedule as $schedule)
                        <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                            <div>
                                <p class="font-medium text-gray-900">{{ $schedule->course->name }}</p>
                                <p class="text-sm text-gray-500">{{ $schedule->teacher->full_name }} &middot; {{ $schedule->classroom ?? 'No room' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No classes scheduled for today</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Announcements</h3>
            </div>
            <div class="card-body p-0">
                <div class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-primary-700">{{ $announcement->author->initials }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $announcement->title }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5">{{ Str::limit($announcement->content, 120) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $announcement->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No announcements</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection