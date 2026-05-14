@extends('layouts.app')

@section('title', $level->name)
@section('page-title', $level->name)
@section('page-subtitle', 'Level details')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Grades in this Level</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Grade Name</th>
                                <th>Code</th>
                                <th>Classes</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($level->grades as $grade)
                                <tr>
                                    <td class="font-medium">{{ $grade->name }}</td>
                                    <td><span class="badge-gray">{{ $grade->code }}</span></td>
                                    <td>{{ $grade->classes->count() }}</td>
                                    <td>
                                        <span class="badge-{{ $grade->is_active ? 'success' : 'danger' }}">
                                            {{ $grade->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td><a href="{{ route('admin.grades.show', $grade) }}" class="text-sm text-primary-600">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-gray-500 py-8">No grades in this level</td></tr>
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
                    <p class="font-medium">{{ $level->code }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Arabic Name</p>
                    <p class="font-medium">{{ $level->name_ar ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Description</p>
                    <p class="text-sm text-gray-700">{{ $level->description ?? 'No description' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sort Order</p>
                    <p class="font-medium">{{ $level->sort_order }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="badge-{{ $level->is_active ? 'success' : 'danger' }}">{{ $level->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Created</p>
                    <p class="text-sm">{{ $level->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.levels.edit', $level) }}" class="btn-primary">Edit Level</a>
        <a href="{{ route('admin.levels.index') }}" class="btn-outline">Back to Levels</a>
    </div>
</div>
@endsection