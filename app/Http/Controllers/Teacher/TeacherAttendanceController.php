<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        $courses = Course::with(['classe.grade.level'])
            ->where('teacher_id', auth()->id())
            ->active()
            ->get();

        return view('attendances.teacher-index', compact('courses'));
    }

    public function mark(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) abort(403);

        $students = $course->classe->students()
            ->wherePivot('status', 'approved')
            ->get();

        $todayAttendance = Attendance::where('course_id', $course->id)
            ->whereDate('date', now()->toDateString())
            ->get()
            ->keyBy('student_id');

        return view('attendances.mark', compact('course', 'students', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'date' => ['required', 'date_format:Y-m-d'],
            'attendances' => ['required', 'array'],
            'attendances.*.student_id' => ['required', 'exists:users,id'],
            'attendances.*.status' => ['required', 'in:present,absent,late,excused'],
            'attendances.*.notes' => ['nullable', 'string', 'max:500'],
        ]);

        $course = Course::findOrFail($data['course_id']);
        if ($course->teacher_id !== auth()->id()) abort(403);

        foreach ($data['attendances'] as $att) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $att['student_id'],
                    'course_id' => $data['course_id'],
                    'date' => $data['date'],
                ],
                [
                    'status' => $att['status'],
                    'notes' => $att['notes'] ?? null,
                    'marked_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('teacher.attendances.index')
            ->with('success', 'Présence marquée avec succès.');
    }

    public function history(Course $course)
    {
        if ($course->teacher_id !== auth()->id()) abort(403);

        $attendances = Attendance::with(['student.studentProfile'])
            ->where('course_id', $course->id)
            ->latest('date')
            ->paginate(30);

        return view('attendances.history', compact('course', 'attendances'));
    }
}