<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LandingPageController extends Controller
{
    public function home()
    {
        $classes = Classe::with([
            'grade.level',
            'homeroomTeacher.teacherProfile',
            'courses.teacher.teacherProfile',
            'schedules',
        ])
            ->public()
            ->active()
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'name_ar' => $c->name_ar,
                'image' => $c->image,
                'price' => $c->price,
                'capacity' => $c->capacity,
                'enrolled_count' => $c->enrolled_count,
                'remaining_seats' => $c->remaining_seats,
                'description' => $c->description,
                'is_full' => $c->remaining_seats <= 0,
                'grade' => $c->grade?->name,
                'level' => $c->grade?->level?->name,
                'homeroom_teacher' => $c->homeroomTeacher?->full_name,
                'courses_count' => $c->courses->count(),
                'schedules_count' => $c->schedules->count(),
            ]);

        $levels = \App\Models\Level::active()->ordered()->get();

        return view('welcome', compact('classes', 'levels'));
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