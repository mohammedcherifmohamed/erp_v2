<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $teachers = User::byRole('teacher')
            ->whereHas('teacherProfile', fn($q) => $q->approved())
            ->with('teacherProfile')
            ->withCount('coursesTeaching')
            ->when($query, fn($q) => $q->where(function($sq) use ($query) {
                $sq->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhereHas('teacherProfile', fn($pq) => $pq->where('specialization', 'like', "%{$query}%"));
            }))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('teachers._table', compact('teachers'))->render();
        }

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'id_card_number' => ['nullable', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => 'teacher',
        ]);

        $user->teacherProfile()->create([
            'arabic_name' => $data['arabic_name'] ?? null,
            'gender' => $data['gender'],
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'id_card_number' => $data['id_card_number'] ?? null,
            'hire_date' => $data['hire_date'] ?? null,
            'hourly_rate' => $data['hourly_rate'] ?? 0,
            'specialization' => $data['specialization'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);

        $this->auditService->logCreate($user, $user->toArray());

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Enseignant créé avec succès.');
    }

    public function show(User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);

        $teacher->load([
            'teacherProfile',
            'coursesTeaching.classe',
            'schedules',
            'teacherContracts',
        ]);

        return view('teachers.show', compact('teacher'));
    }

    public function edit(User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);
        $teacher->load('teacherProfile');
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);

        $data = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $teacher->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['sometimes', 'string', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'id_card_number' => ['nullable', 'string', 'max:50'],
            'hire_date' => ['nullable', 'date'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['sometimes', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $userFields = ['first_name', 'last_name', 'email', 'phone'];

        if ($data['password'] ?? false) {
            $teacher->update(['password' => Hash::make($data['password'])]);
        }
        $profileFields = ['arabic_name', 'gender', 'date_of_birth', 'nationality', 'id_card_number', 'hire_date', 'hourly_rate', 'specialization', 'bio', 'is_active'];

        $teacher->update(collect($data)->only($userFields)->toArray());

        if ($teacher->teacherProfile) {
            $teacher->teacherProfile()->update(collect($data)->only($profileFields)->toArray());
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Enseignant mis à jour avec succès.');
    }

    public function pending()
    {
        $teachers = User::byRole('teacher')
            ->whereHas('teacherProfile', fn($q) => $q->pending())
            ->with('teacherProfile')
            ->latest()
            ->paginate(15);

        return view('teachers.pending', compact('teachers'));
    }

    public function approve(User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);

        $teacher->teacherProfile->update(['status' => 'approved']);

        return redirect()->route('admin.teachers.pending')
            ->with('success', 'Enseignant approuvé avec succès. Définissez son mot de passe depuis la page de modification.');
    }

    public function reject(Request $request, User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);

        $data = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $teacher->teacherProfile->update([
            'status' => 'rejected',
            'bio' => $data['rejection_reason']
                ? ($teacher->teacherProfile->bio ? $teacher->teacherProfile->bio . "\n\nMotif de refus: " . $data['rejection_reason'] : 'Motif de refus: ' . $data['rejection_reason'])
                : $teacher->teacherProfile->bio,
        ]);

        return redirect()->route('admin.teachers.pending')
            ->with('success', 'Candidature refusée.');
    }

    public function destroy(User $teacher)
    {
        if (!$teacher->isTeacher()) abort(404);

        $teacher->teacherProfile()->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Enseignant supprimé.');
    }
}