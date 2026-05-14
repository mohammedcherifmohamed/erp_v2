@extends('layouts.app')

@section('title', 'Parent Dashboard')
@section('page-title', 'Parent Dashboard')
@section('page-subtitle', 'Monitor your children\'s progress')

@section('content')
<div class="space-y-6">
    @forelse($children as $child)
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="avatar avatar-md bg-primary-100 text-primary-700">
                        {{ $child->initials }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $child->full_name }}</h3>
                        <p class="text-sm text-gray-500">{{ $child->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('parent.children.schedule', $child) }}" class="btn-sm btn-outline">Schedule</a>
                    <a href="{{ route('parent.children.invoices', $child) }}" class="btn-sm btn-outline">Invoices</a>
                </div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-primary-600">{{ $child->enrollments->count() }}</p>
                        <p class="text-sm text-gray-500">Enrollments</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-success-600">{{ $child->enrollments->where('status', 'approved')->count() }}</p>
                        <p class="text-sm text-gray-500">Active</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-2xl font-bold text-warning-600">{{ $child->attendanceRecords->where('status', 'absent')->count() }}</p>
                        <p class="text-sm text-gray-500">Absences</p>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">No children linked</h3>
                <p class="text-sm text-gray-500 mt-1">Contact the administration to link your children to your account.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection