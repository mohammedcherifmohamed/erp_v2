<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Models\Classe;
use App\Models\Course;
use App\Models\User;
use App\Services\AuditService;

class CourseController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $courses = Course::with(['classe.grade.level', 'teacher'])
            ->withCount(['schedules', 'quizzes'])
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('courses._table', compact('courses'))->render();
        }

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        $classes = Classe::with('grade.level')->active()->get();
        $teachers = User::byRole('teacher')->get();
        return view('courses.create', compact('classes', 'teachers'));
    }

    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());
        $this->auditService->logCreate($course, $course->toArray());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function show(Course $course)
    {
        $course->load(['classe.grade.level', 'teacher', 'schedules', 'quizzes']);
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $classes = Classe::with('grade.level')->active()->get();
        $teachers = User::byRole('teacher')->get();
        return view('courses.edit', compact('course', 'classes', 'teachers'));
    }

    public function update(StoreCourseRequest $request, Course $course)
    {
        $old = $course->toArray();
        $course->update($request->validated());
        $this->auditService->logUpdate($course, $old, $course->toArray());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Cours mis à jour avec succès.');
    }

    public function destroy(Course $course)
    {
        $this->auditService->logDelete($course, $course->toArray());
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Cours supprimé avec succès.');
    }
}