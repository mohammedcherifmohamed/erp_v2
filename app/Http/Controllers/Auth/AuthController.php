<?php

namespace App\Http\Controllers\Auth;

use App\Events\StudentRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function showLogin(Request $request)
    {
        if ($request->has('redirect')) {
            $request->session()->put('url.intended', $request->redirect);
        }

        return view('auth.login');
    }

    public function showTeacherLogin()
    {
        return view('auth.teacher-login');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $this->auditService->logAuth('login', ['email' => $request->email]);

        return redirect()->intended($this->redirectTo());
    }

    public function logout(Request $request)
    {
        $this->auditService->logAuth('logout');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'student',
        ]);

        $user->studentProfile()->create([]);

        Auth::login($user);

        $this->auditService->logAuth('register', ['user_id' => $user->id]);

        event(new StudentRegistered($user));

        return redirect()->route('dashboard');
    }

    protected function redirectTo(): string
    {
        return match (auth()->user()->role) {
            'admin' => route('admin.dashboard'),
            'teacher' => route('teacher.dashboard'),
            'student' => route('student.dashboard'),
            'parent' => route('parent.dashboard'),
            default => route('dashboard'),
        };
    }
}