@extends('layouts.app')

@section('title', $parent->full_name)
@section('page-title', $parent->full_name)
@section('page-subtitle', 'Parent details')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <div class="card-body text-center space-y-3">
                <div class="avatar avatar-xl bg-primary-100 text-primary-700 mx-auto text-2xl">
                    {{ $parent->initials }}
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $parent->full_name }}</h3>
                    <p class="text-sm text-gray-500">{{ $parent->email }}</p>
                </div>
                <div class="text-left space-y-2 pt-3 border-t">
                    <div><p class="text-sm text-gray-500">Phone</p><p class="text-sm font-medium">{{ $parent->phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Secondary Phone</p><p class="text-sm font-medium">{{ $parent->secondary_phone ?? '-' }}</p></div>
                    <div><p class="text-sm text-gray-500">Relationship</p><p class="text-sm font-medium">{{ ucfirst($parent->relationship ?? '-') }}</p></div>
                    <div><p class="text-sm text-gray-500">Profession</p><p class="text-sm font-medium">{{ $parent->profession ?? '-' }}</p></div>
                </div>
            </div>
        </div>
        <div class="card lg:col-span-2">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900">Linked Children</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parent->children as $student)
                                <tr>
                                    <td class="font-medium">{{ $student->full_name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->enrollments->first()?->class?->grade->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge-{{ $student->is_active ? 'success' : 'danger' }}">{{ $student->is_active ? 'Active' : 'Inactive' }}</span>
                                    </td>
                                    <td><a href="{{ route('admin.students.show', $student) }}" class="text-sm text-primary-600">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-gray-500 py-8">No linked children</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-header border-t">
                <h3 class="font-semibold text-gray-900">Invoices Overview</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Student</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parent->invoices as $invoice)
                                <tr>
                                    <td class="font-medium">{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->student->full_name ?? '-' }}</td>
                                    <td>{{ number_format($invoice->total, 2) }}</td>
                                    <td>{{ number_format($invoice->paid, 2) }}</td>
                                    <td>
                                        <span class="badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $invoice->due_date->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-gray-500 py-8">No invoices</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.parents.edit', $parent) }}" class="btn-primary">Edit Parent</a>
        <a href="{{ route('admin.parents.index') }}" class="btn-outline">Back to Parents</a>
    </div>
</div>
@endsection
