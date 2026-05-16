<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Classe;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\EnrollmentService;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentService $enrollmentService,
    ) {}

    public function index()
    {
        $query = request('query');
        $enrollments = Enrollment::with(['student.studentProfile', 'classe.grade.level', 'classe.courses', 'course'])
            ->when($query, fn($q) => $q->whereHas('student', fn($sq) => $sq
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('enrollments._table', compact('enrollments'))->render();
        }

        return view('enrollments.index', compact('enrollments'));
    }

    public function pending()
    {
        $query = request('query');

        $enrollments = Enrollment::with(['student.studentProfile', 'classe.grade.level', 'classe.courses', 'course'])
            ->pending()
            ->when($query, fn($q) => $q->whereHas('student', fn($sq) => $sq
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('enrollments._pending_table', compact('enrollments'))->render();
        }

        return view('enrollments.pending', compact('enrollments'));
    }

    public function create()
    {
        $students = User::byRole('student')->get();
        $classes = Classe::with('grade.level')->active()->get();
        return view('enrollments.create', compact('students', 'classes'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = $this->enrollmentService->submit($request->validated());

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Inscription soumise avec succès.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.studentProfile', 'student.parentProfile', 'classe.grade.level', 'classe.courses', 'course', 'approver']);
        return view('enrollments.show', compact('enrollment'));
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        try {
            $this->enrollmentService->approve($enrollment, $data['start_date'], $data['end_date']);
            return back()->with('success', 'Inscription approuvée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Enrollment $enrollment)
    {
        $data = request()->validate(['rejection_reason' => ['required', 'string', 'max:500']]);
        $this->enrollmentService->reject($enrollment, $data['rejection_reason']);

        return back()->with('success', 'Inscription rejetée.');
    }

    public function archive(Enrollment $enrollment)
    {
        $this->enrollmentService->archive($enrollment);
        return back()->with('success', 'Inscription archivée.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Inscription supprimée.');
    }
}