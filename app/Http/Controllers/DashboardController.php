<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'teacher' => $this->teacherDashboard(),
            'student' => $this->studentDashboard(),
            'parent' => $this->parentDashboard(),
            default => redirect()->route('login'),
        };
    }

    public function adminDashboard()
    {
        $stats = [
            'total_students' => User::byRole('student')->count(),
            'total_teachers' => User::byRole('teacher')->count(),
            'total_parents' => User::byRole('parent')->count(),
            'total_classes' => Classe::count(),
            'total_courses' => Course::count(),
            'pending_enrollments' => Enrollment::pending()->count(),
            'total_invoices' => Invoice::count(),
            'unpaid_invoices' => Invoice::unpaid()->count(),
            'total_revenue' => Invoice::sum('paid_amount'),
            'pending_revenue' => Invoice::unpaid()->sum('remaining_amount'),
        ];

        $recentEnrollments = Enrollment::with(['student', 'classe.grade.level'])
            ->latest()
            ->take(5)
            ->get();

        $announcements = Announcement::with('author')
            ->published()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentEnrollments', 'announcements'));
    }

    public function teacherDashboard()
    {
        $teacherId = auth()->id();

        $stats = [
            'total_courses' => Course::where('teacher_id', $teacherId)->count(),
            'total_schedules' => \App\Models\Schedule::forTeacher($teacherId)->count(),
            'total_quizzes' => Quiz::forTeacher($teacherId)->count(),
            'wallet_balance' => auth()->user()->teacherProfile->wallet_balance ?? 0,
            'pending_balance' => auth()->user()->teacherProfile->pending_balance ?? 0,
        ];

        $todaySchedule = \App\Models\Schedule::with(['course', 'classe'])
            ->forTeacher($teacherId)
            ->byDay(strtolower(now()->format('l')))
            ->get();

        $courses = Course::with(['classe.grade.level'])
            ->where('teacher_id', $teacherId)
            ->get();

        return view('dashboard.teacher', compact('stats', 'todaySchedule', 'courses'));
    }

    public function studentDashboard()
    {
        $studentId = auth()->id();

        $enrollments = Enrollment::with(['classe.grade.level', 'classe.courses'])
            ->where('student_id', $studentId)
            ->approved()
            ->get();

        $classIds = $enrollments->pluck('class_id');

        $stats = [
            'total_enrollments' => $enrollmentCount = Enrollment::where('student_id', $studentId)->approved()->count(),
            'total_courses' => Course::whereIn('class_id', $classIds)->count(),
            'upcoming_schedules' => \App\Models\Schedule::with(['course', 'teacher'])
                ->whereIn('class_id', $classIds)
                ->count(),
            'pending_invoices' => Invoice::where('student_id', $studentId)->unpaid()->count(),
        ];

        $announcements = Announcement::with('author')
            ->forClass($classIds->toArray())
            ->published()
            ->latest()
            ->take(5)
            ->get();

        $todaySchedule = \App\Models\Schedule::with(['course', 'teacher'])
            ->whereIn('class_id', $classIds)
            ->byDay(strtolower(now()->format('l')))
            ->get();

        return view('dashboard.student', compact('stats', 'enrollments', 'announcements', 'todaySchedule'));
    }

    public function parentDashboard()
    {
        $parentId = auth()->id();
        $children = User::whereHas('studentProfile', fn($q) => $q->where('parent_id', $parentId))->get();

        return view('dashboard.parent', compact('children'));
    }
}