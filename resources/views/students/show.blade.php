@extends('layouts.app')

@section('title', $student->full_name)
@section('page-title', $student->full_name)
@section('page-subtitle', 'Student profile')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card lg:col-span-1">
            <div class="card-body text-center space-y-3">
                <div class="avatar avatar-xl bg-primary-100 text-primary-700 mx-auto text-2xl">
                    {{ $student->initials }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $student->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $student->email }}</p>
                </div>
                <span class="badge-{{ $student->is_active ? 'success' : 'danger' }} inline-block">
                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                </span>
                <div class="text-left space-y-2 pt-3 border-t">
                    <div><p class="text-sm text-gray-500">Phone</p><p class="text-sm font-medium">{{ $student->phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Gender</p><p class="text-sm font-medium">{{ ucfirst($student->gender ?? '-') }}</p></div>
                    <div><p class="text-sm text-gray-500">Blood Type</p><p class="text-sm font-medium">{{ $student->blood_type ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Parent</p><p class="text-sm font-medium">{{ $student->parent->full_name ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Date of Birth</p><p class="text-sm font-medium">{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : '-' }}</p></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Enrollments</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Grade / Level</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->enrollments as $enrollment)
                                    <tr>
                                        <td class="font-medium">{{ $enrollment->class->name ?? '-' }}</td>
                                        <td>{{ $enrollment->class->grade->name ?? '-' }} / {{ $enrollment->class->grade->level->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge-{{ $enrollment->status === 'active' ? 'success' : ($enrollment->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $enrollment->created_at->format('M d, Y') }}</td>
                                        <td><a href="{{ route('admin.enrollments.show', $enrollment) }}" class="text-sm text-primary-600">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-gray-500 py-8">No enrollments</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Invoices</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->invoices as $invoice)
                                    <tr>
                                        <td class="font-medium">{{ $invoice->invoice_number }}</td>
                                        <td>{{ number_format($invoice->total, 2) }}</td>
                                        <td>{{ number_format($invoice->paid, 2) }}</td>
                                        <td>{{ number_format($invoice->remaining, 2) }}</td>
                                        <td>
                                            <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                        <td><a href="{{ route('admin.invoices.show', $invoice) }}" class="text-sm text-primary-600">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-gray-500 py-8">No invoices</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-gray-900">Attendance (Recent)</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Marked By</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @forelse($student->attendanceRecords->take(10) as $attendance)
                                     <tr>
                                         <td>{{ $attendance->date->format('M d, Y') }}</td>
                                         <td>{{ $attendance->course->name ?? '-' }}</td>
                                         <td>
                                             <span class="badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'absent' ? 'danger' : ($attendance->status === 'late' ? 'warning' : 'gray')) }}">
                                                 {{ ucfirst($attendance->status) }}
                                             </span>
                                         </td>
                                         <td>{{ $attendance->marker->full_name ?? '-' }}</td>
                                     </tr>
                                 @empty
                                    <tr><td colspan="4" class="text-center text-gray-500 py-8">No attendance records</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn-primary">Edit Student</a>
        <a href="{{ route('admin.students.index') }}" class="btn-outline">Back to Students</a>
    </div>
</div>
@endsection
