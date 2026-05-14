@extends('layouts.app')

@section('title', 'Enrollment Details')
@section('page-title', 'Enrollment Details')
@section('page-subtitle', $enrollment->student->full_name ?? '')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Enrollment Information</h3>
            </div>
            <div class="card-body space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Student</p>
                        <p class="font-medium">{{ $enrollment->student->full_name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="font-medium">{{ $enrollment->class->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Grade / Level</p>
                        <p class="font-medium">{{ $enrollment->class->grade->name ?? '-' }} / {{ $enrollment->class->grade->level->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="badge-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : ($enrollment->status === 'rejected' ? 'danger' : 'gray')) }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Enrolled Date</p>
                        <p class="font-medium">{{ $enrollment->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approved By</p>
                        <p class="font-medium">{{ $enrollment->approvedBy->full_name ?? 'Not approved' }}</p>
                    </div>
                </div>
                @if($enrollment->notes)
                    <div>
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-sm text-gray-700">{{ $enrollment->notes }}</p>
                    </div>
                @endif
            </div>
            @if($enrollment->status === 'pending')
                <div class="card-footer border-t flex gap-3">
                    <form method="POST" action="{{ route('admin.enrollments.approve', $enrollment) }}">
                        @csrf
                        <button type="submit" class="btn-success">Approve Enrollment</button>
                    </form>
                    <form method="POST" action="{{ route('admin.enrollments.reject', $enrollment) }}">
                        @csrf
                        <button type="submit" class="btn-danger" onclick="return confirm('Reject this enrollment?')">Reject Enrollment</button>
                    </form>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Student Info</h3>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-sm">{{ $enrollment->student->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-sm">{{ $enrollment->student->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Parent</p>
                    <p class="text-sm">{{ $enrollment->student->parent->full_name ?? '-' }}</p>
                </div>
                <a href="{{ route('admin.students.show', $enrollment->student) }}" class="btn-outline w-full text-center text-sm">View Student Profile</a>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.enrollments.index') }}" class="btn-outline">Back to Enrollments</a>
    </div>
</div>
@endsection
