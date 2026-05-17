<?php

namespace App\Http\Controllers\Admin;

use App\Events\AccountCreatedByAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $students = User::byRole('student')
            ->with('studentProfile')
            ->when($query, fn($q) => $q->where(function($sq) use ($query) {
                $sq->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            }))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('students._table', compact('students'))->render();
        }

        return view('students.index', compact('students'));
    }

    public function create()
    {
        $parents = User::byRole('parent')->with('parentProfile')->get();
        return view('students.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:20'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:users,id'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? \Illuminate\Support\Str::random(16)),
            'phone' => $data['phone'] ?? null,
            'role' => 'student',
        ]);

        $user->studentProfile()->create([
            'arabic_name' => $data['arabic_name'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'blood_type' => $data['blood_type'] ?? null,
            'allergies' => $data['allergies'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        $this->auditService->logCreate($user, $user->toArray());

        event(new AccountCreatedByAdmin($user));

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $student->load([
            'studentProfile',
            'enrollments.classe.grade.level',
            'enrollments.classe.courses',
            'enrollments.course',
            'invoices',
            'attendanceRecords' => fn($q) => $q->latest()->take(30),
        ]);

        return view('students.show', compact('student'));
    }

    public function edit(User $student)
    {
        if (!$student->isStudent()) abort(404);
        $student->load('studentProfile');
        $parents = User::byRole('parent')->with('parentProfile')->get();

        return view('students.edit', compact('student', 'parents'));
    }

    public function update(Request $request, User $student)
    {
        if (!$student->isStudent()) abort(404);

        $data = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $student->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:20'],
            'blood_type' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $student->update($data);
        $student->studentProfile()->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(User $student)
    {
        if (!$student->isStudent()) abort(404);

        $student->studentProfile()->delete();
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant supprimé.');
    }
}