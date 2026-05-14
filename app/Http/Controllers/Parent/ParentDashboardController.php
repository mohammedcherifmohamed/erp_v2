<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\User;

class ParentDashboardController extends Controller
{
    public function children()
    {
        $children = User::whereHas('studentProfile', fn($q) => $q->where('parent_id', auth()->id()))
            ->with(['studentProfile', 'enrollments.classe.grade.level', 'attendanceRecords' => fn($q) => $q->latest()->take(10)])
            ->get();

        return view('parent.children', compact('children'));
    }

    public function childInvoices(User $student)
    {
        if ($student->studentProfile->parent_id !== auth()->id()) abort(403);

        $invoices = Invoice::with(['payments', 'classe'])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(15);

        return view('invoices.parent-index', compact('invoices', 'student'));
    }

    public function childInvoiceShow(Invoice $invoice)
    {
        if ($invoice->parent_id !== auth()->id()) abort(403);
        $invoice->load(['payments', 'classe', 'student.studentProfile']);

        return view('invoices.parent-show', compact('invoice'));
    }

    public function childSchedule(User $student)
    {
        if ($student->studentProfile->parent_id !== auth()->id()) abort(403);

        $classIds = $student->enrollments()->approved()->pluck('class_id');
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $schedules = \App\Models\Schedule::with(['course', 'teacher'])
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        $week = collect();
        foreach ($days as $day) {
            $week[$day] = $schedules->get($day, collect());
        }

        return view('schedules.parent', compact('week', 'student'));
    }
}