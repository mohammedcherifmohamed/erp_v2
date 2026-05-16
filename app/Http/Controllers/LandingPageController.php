<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LandingPageController extends Controller
{
    public function home()
    {
        $publicClasses = Classe::with([
            'grade.level',
            'homeroomTeacher.teacherProfile',
            'courses',
            'schedules',
        ])
            ->public()
            ->active()
            ->get();

        $levels = \App\Models\Level::active()->ordered()->get();

        return view('welcome', compact('publicClasses', 'levels'));
    }

    public function courses()
    {
        $query = request('query');
        $levelId = request('level_id');
        $sort = request('sort', 'name');

        $classes = Classe::with([
            'grade.level',
            'homeroomTeacher.teacherProfile',
            'courses.teacher.teacherProfile',
            'schedules',
        ])
            ->public()
            ->active()
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhereHas('grade', fn($g) => $g->where('name', 'like', "%{$query}%")
                    ->orWhereHas('level', fn($l) => $l->where('name', 'like', "%{$query}%"))))
            ->when($levelId, fn($q) => $q->whereHas('grade', fn($g) => $g->where('level_id', $levelId)))
            ->when($sort === 'price_asc', fn($q) => $q->orderBy('price'))
            ->when($sort === 'price_desc', fn($q) => $q->orderByDesc('price'))
            ->when($sort === 'name', fn($q) => $q->orderBy('name'))
            ->paginate(12)
            ->withQueryString();

        $levels = \App\Models\Level::active()->ordered()->get();

        if (request()->ajax()) {
            return view('public._courses_grid', compact('classes'))->render();
        }

        return view('public.courses', compact('classes', 'levels'));
    }

    public function courseDetails(Classe $classe)
    {
        if (!$classe->is_public || !$classe->is_active) {
            abort(404);
        }

        $classe->load([
            'grade.level',
            'homeroomTeacher.teacherProfile',
            'courses.teacher.teacherProfile',
            'enrollments',
            'schedules.course',
        ]);

        return view('public.course-details', compact('classe'));
    }

    public function enroll(Request $request, Classe $classe)
    {
        if (!$classe->is_public || !$classe->is_active) {
            return redirect()->route('courses')->with('error', 'Cette classe n\'est pas disponible.');
        }

        if ($classe->remaining_seats <= 0) {
            return back()->with('error', 'Désolé, cette classe est complète.');
        }

        $user = $request->user();

        if (!$user->isStudent()) {
            return back()->with('error', 'Seuls les étudiants peuvent s\'inscrire aux cours.');
        }

        $existing = Enrollment::where('student_id', $user->id)
            ->where('class_id', $classe->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Vous êtes déjà inscrit à cette classe ou votre demande est en attente.');
        }

        $enrollment = Enrollment::create([
            'student_id' => $user->id,
            'class_id' => $classe->id,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('enrollments.success', $enrollment);
    }

    public function enrollCourse(Request $request, Course $course)
    {
        $classe = $course->classe;

        if (!$classe->is_public || !$classe->is_active) {
            return redirect()->route('courses')->with('error', 'Ce cours n\'est pas disponible.');
        }

        $user = $request->user();

        if (!$user->isStudent()) {
            return back()->with('error', 'Seuls les étudiants peuvent s\'inscrire aux cours.');
        }

        $existing = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Vous êtes déjà inscrit à ce cours ou votre demande est en attente.');
        }

        $enrollment = Enrollment::create([
            'student_id' => $user->id,
            'class_id' => $classe->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return redirect()->route('enrollments.success', $enrollment);
    }

    public function enrollmentSuccess(Enrollment $enrollment)
    {
        if ($enrollment->student_id !== auth()->id()) {
            abort(403);
        }

        $enrollment->load(['classe.grade.level', 'classe.courses', 'course']);
        return view('enrollments.success', compact('enrollment'));
    }

    public function teacherRegister()
    {
        $specializations = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology',
            'Arabic Language', 'French Language', 'English Language',
            'History', 'Geography', 'Computer Science',
            'Physical Education', 'Art', 'Music',
        ];

        return view('public.teacher-register', compact('specializations'));
    }

    public function teacherRegisterStore(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'specialization' => ['required', 'string', 'max:255'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'gender' => ['required', 'in:male,female'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make(\Str::random(16)),
            'phone' => $validated['phone'],
            'role' => 'teacher',
        ]);

        $profileData = [
            'gender' => $validated['gender'],
            'specialization' => $validated['specialization'],
            'bio' => $validated['message'] ?? null,
        ];

        $user->teacherProfile()->create($profileData);

        if ($request->hasFile('cv')) {
            $user->teacherProfile->update([
                'cv_path' => $request->file('cv')->store('teacher-cvs', 'public'),
            ]);
        }

        return redirect()->route('home')
            ->with('success', 'Votre candidature a été envoyée avec succès. Nous vous contacterons bientôt.');
    }
}