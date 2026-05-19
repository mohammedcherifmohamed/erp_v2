<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SectionEnrollment;
use App\Services\AuditService;
use App\Services\SectionEnrollmentService;
use Illuminate\Http\Request;

class SectionEnrollmentController extends Controller
{
    public function __construct(
        private readonly SectionEnrollmentService $sectionEnrollmentService,
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $status = request('status');

        $enrollments = SectionEnrollment::with([
            'student.studentProfile',
            'section.grade.level',
            'section.courses',
        ])
            ->when($query, fn($q) => $q->whereHas('student', fn($sq) => $sq
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
            ))
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('section-enrollments._table', compact('enrollments'))->render();
        }

        return view('section-enrollments.index', compact('enrollments'));
    }

    public function show(SectionEnrollment $sectionEnrollment)
    {
        $sectionEnrollment->load([
            'student.studentProfile',
            'section.grade.level',
            'section.courses.teacher',
            'approver',
        ]);

        return view('section-enrollments.show', compact('sectionEnrollment'));
    }

    public function pending()
    {
        $enrollments = SectionEnrollment::with([
            'student.studentProfile',
            'section.grade.level',
            'section.courses',
        ])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('section-enrollments.pending', compact('enrollments'));
    }

    public function approve(Request $request, SectionEnrollment $sectionEnrollment)
    {
        $data = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ]);

        $this->sectionEnrollmentService->approve(
            $sectionEnrollment,
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
        );

        return redirect()->route('admin.section-enrollments.pending')
            ->with('success', 'Inscription au forfait approuvée avec succès. Les inscriptions aux cours ont été créées automatiquement.');
    }

    public function reject(Request $request, SectionEnrollment $sectionEnrollment)
    {
        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $this->sectionEnrollmentService->reject($sectionEnrollment, $data['rejection_reason']);

        return redirect()->route('admin.section-enrollments.pending')
            ->with('success', 'Inscription au forfait refusée.');
    }

    public function archive(SectionEnrollment $sectionEnrollment)
    {
        $this->sectionEnrollmentService->archive($sectionEnrollment);

        return redirect()->route('admin.section-enrollments.index')
            ->with('success', 'Inscription au forfait archivée.');
    }
}
