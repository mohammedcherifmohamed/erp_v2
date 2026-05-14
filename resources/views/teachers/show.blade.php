@extends('layouts.app')

@section('title', $teacher->full_name)
@section('page-title', $teacher->full_name)
@section('page-subtitle', 'Teacher profile')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card lg:col-span-1">
            <div class="card-body text-center space-y-3">
                <div class="avatar avatar-xl bg-primary-100 text-primary-700 mx-auto text-2xl">
                    {{ $teacher->initials }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $teacher->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $teacher->email }}</p>
                </div>
                <span class="badge-{{ $teacher->is_active ? 'success' : 'danger' }} inline-block">
                    {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                </span>
                <div class="text-left space-y-2 pt-3 border-t">
                    <div><p class="text-sm text-gray-500">Phone</p><p class="text-sm font-medium">{{ $teacher->phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Gender</p><p class="text-sm font-medium">{{ ucfirst($teacher->gender ?? '-') }}</p></div>
                    <div><p class="text-sm text-gray-500">Specialization</p><p class="text-sm font-medium">{{ $teacher->specialization ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Nationality</p><p class="text-sm font-medium">{{ $teacher->nationality ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Hourly Rate</p><p class="text-sm font-medium">{{ number_format($teacher->hourly_rate ?? 0, 2) }}</p></div>
                    <div><p class="text-sm text-gray-500">Wallet Balance</p><p class="text-sm font-semibold text-primary-600">{{ number_format($teacher->wallet_balance ?? 0, 2) }}</p></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Courses</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Code</th>
                                    <th>Class</th>
                                    <th>Sessions</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->coursesTeaching as $course)
                                    <tr>
                                        <td class="font-medium">{{ $course->name }}</td>
                                        <td><span class="badge-gray">{{ $course->code }}</span></td>
                                        <td>{{ $course->class->name ?? '-' }}</td>
                                        <td>{{ $course->sessions_count }}</td>
                                        <td>
                                            <span class="badge-{{ $course->is_active ? 'success' : 'danger' }}">{{ $course->is_active ? 'Active' : 'Inactive' }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.courses.show', $course) }}" class="text-sm text-primary-600">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-gray-500 py-8">No courses assigned</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Schedule</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Course</th>
                                    <th>Class</th>
                                    <th>Time</th>
                                    <th>Classroom</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->schedules as $schedule)
                                    <tr>
                                        <td class="font-medium">{{ $schedule->day_of_week }}</td>
                                        <td>{{ $schedule->course->name ?? '-' }}</td>
                                        <td>{{ $schedule->course->class->name ?? '-' }}</td>
                                        <td>{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                                        <td>{{ $schedule->classroom ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-8">No schedules</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Contracts</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Rate</th>
                                    <th>Course / Class</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->teacherContracts as $contract)
                                    <tr>
                                        <td class="font-medium">{{ ucfirst($contract->contract_type) }}</td>
                                        <td>{{ number_format($contract->rate, 2) }}</td>
                                        <td>{{ $contract->course->name ?? $contract->class->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge-{{ $contract->is_active ? 'success' : 'danger' }}">{{ $contract->is_active ? 'Active' : 'Inactive' }}</span>
                                        </td>
                                        <td><a href="{{ route('admin.teacher-contracts.show', $contract) }}" class="text-sm text-primary-600">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-8">No contracts</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Wallet Transactions</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teacher->walletTransactions as $txn)
                                    <tr>
                                        <td>{{ $txn->created_at->format('M d, Y') }}</td>
                                        <td>{{ $txn->description }}</td>
                                        <td class="font-medium {{ $txn->amount > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                            {{ $txn->amount > 0 ? '+' : '' }}{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td><span class="badge-{{ $txn->type === 'credit' ? 'success' : 'danger' }}">{{ ucfirst($txn->type) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-gray-500 py-8">No transactions</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn-primary">Edit Teacher</a>
        <a href="{{ route('admin.teachers.index') }}" class="btn-outline">Back to Teachers</a>
    </div>
</div>
@endsection
