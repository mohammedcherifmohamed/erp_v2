@extends('layouts.app')

@section('title', 'Contract Details')
@section('page-title', 'Contract Details')
@section('page-subtitle', $contract->teacher->full_name ?? '')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Contract Information</h3>
        </div>
        <div class="card-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Teacher</p>
                    <p class="font-medium">{{ $contract->teacher->full_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $contract->teacher->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Contract Type</p>
                    <span class="badge-gray inline-block mt-1">{{ ucfirst($contract->contract_type) }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rate</p>
                    <p class="text-lg font-semibold">{{ number_format($contract->rate, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $contract->is_active ? 'success' : 'danger' }} inline-block mt-1">
                        {{ $contract->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="font-medium">{{ $contract->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            @if($contract->course || $contract->class)
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Assigned To</p>
                    <div class="grid grid-cols-2 gap-4">
                        @if($contract->course)
                            <div>
                                <p class="text-sm text-gray-500">Course</p>
                                <p class="font-medium">{{ $contract->course->name }}</p>
                            </div>
                        @endif
                        @if($contract->class)
                            <div>
                                <p class="text-sm text-gray-500">Class</p>
                                <p class="font-medium">{{ $contract->class->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer border-t flex items-center justify-end gap-3 px-6 py-4">
            <a href="{{ route('admin.teacher-contracts.edit', $contract) }}" class="btn-primary">Edit</a>
            <a href="{{ route('admin.teacher-contracts.index') }}" class="btn-outline">Back</a>
        </div>
    </div>
</div>
@endsection
