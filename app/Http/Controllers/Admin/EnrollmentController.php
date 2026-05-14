<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Classe;
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
        $enrollments = Enrollment::with(['student.studentProfile', 'classe.grade.level'])
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

        $enrollments = Enrollment::with(['student.studentProfile', 'classe.grade.level'])
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
            ->with('success', 'Enrollment submitted successfully.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.studentProfile', 'student.parentProfile', 'classe.grade.level', 'approver']);
        return view('enrollments.show', compact('enrollment'));
    }

    public function approve(Enrollment $enrollment)
    {
        try {
            $this->enrollmentService->approve($enrollment);
            return back()->with('success', 'Enrollment approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Enrollment $enrollment)
    {
        $data = request()->validate(['rejection_reason' => ['required', 'string', 'max:500']]);
        $this->enrollmentService->reject($enrollment, $data['rejection_reason']);

        return back()->with('success', 'Enrollment rejected.');
    }

    public function archive(Enrollment $enrollment)
    {
        $this->enrollmentService->archive($enrollment);
        return back()->with('success', 'Enrollment archived.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Enrollment deleted.');
    }
}