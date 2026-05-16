<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Classe;
use App\Models\Course;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $query = request('query');
        $attendances = Attendance::with(['student.studentProfile', 'course', 'marker'])
            ->when($query, fn($q) => $q->whereHas('student', fn($sq) => $sq
                ->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%")))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('attendances._table', compact('attendances'))->render();
        }

        return view('attendances.index', compact('attendances'));
    }

    public function create()
    {
        $courses = Course::with(['classe.grade.level', 'teacher'])
            ->active()
            ->get();

        return view('attendances.create', compact('courses'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $courseId = $request->course_id;
        $date = $request->date;

        foreach ($request->attendances as $att) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $att['student_id'],
                    'course_id' => $courseId,
                    'date' => $date,
                ],
                [
                    'status' => $att['status'],
                    'notes' => $att['notes'] ?? null,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Présence marquée avec succès.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load(['student.studentProfile', 'course', 'schedule', 'marker']);
        return view('attendances.show', compact('attendance'));
    }

    public function byCourse(Course $course)
    {
        $students = $course->classe->students()
            ->wherePivot('status', 'approved')
            ->get();

        $attendances = Attendance::where('course_id', $course->id)
            ->whereDate('date', now()->toDateString())
            ->get()
            ->keyBy('student_id');

        return view('attendances.by-course', compact('course', 'students', 'attendances'));
    }

    public function analytics()
    {
        $total = Attendance::count();
        $present = Attendance::present()->count();
        $absent = Attendance::absent()->count();
        $late = Attendance::where('status', 'late')->count();
        $excused = Attendance::where('status', 'excused')->count();

        $presentRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $courseStats = Course::withCount(['attendances as present_count' => fn($q) => $q->present(),
            'attendances as absent_count' => fn($q) => $q->absent(),
        ])->get();

        return view('attendances.analytics', compact('total', 'present', 'absent', 'late', 'excused', 'presentRate', 'courseStats'));
    }

    public function edit(Attendance $attendance)
    {
        return view('attendances.edit', compact('attendance'));
    }

    public function update(StoreAttendanceRequest $request, Attendance $attendance)
    {
        $attendance->update([
            'status' => $request->attendances[0]['status'],
            'notes' => $request->attendances[0]['notes'] ?? null,
        ]);

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Présence mise à jour avec succès.');
    }
}