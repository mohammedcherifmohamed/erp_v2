<?php

namespace App\Http\Controllers\Admin;

use App\Events\AccountCreatedByAdmin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $parents = User::byRole('parent')
            ->with('parentProfile')
            ->withCount(['children'])
            ->when($query, fn($q) => $q->where(function($sq) use ($query) {
                $sq->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            }))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('parents._table', compact('parents'))->render();
        }

        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        return view('parents.create');
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
            'profession' => ['nullable', 'string', 'max:255'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
            'relationship' => ['nullable', 'string', 'in:father,mother,guardian,other'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? \Illuminate\Support\Str::random(16)),
            'phone' => $data['phone'] ?? null,
            'role' => 'parent',
        ]);

        $user->parentProfile()->create([
            'arabic_name' => $data['arabic_name'] ?? null,
            'profession' => $data['profession'] ?? null,
            'secondary_phone' => $data['secondary_phone'] ?? null,
            'relationship' => $data['relationship'] ?? 'father',
        ]);

        $this->auditService->logCreate($user, $user->toArray());

        event(new AccountCreatedByAdmin($user));

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent créé avec succès.');
    }

    public function show(User $parent)
    {
        if (!$parent->isParent()) abort(404);

        $parent->load([
            'parentProfile',
            'children.user',
            'parentInvoices',
        ]);

        return view('parents.show', compact('parent'));
    }

    public function edit(User $parent)
    {
        if (!$parent->isParent()) abort(404);
        $parent->load('parentProfile');
        return view('parents.edit', compact('parent'));
    }

    public function update(Request $request, User $parent)
    {
        if (!$parent->isParent()) abort(404);

        $data = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $parent->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'arabic_name' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
            'relationship' => ['nullable', 'string', 'in:father,mother,guardian,other'],
        ]);

        $parent->update($data);
        $parent->parentProfile()->update($data);

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent mis à jour avec succès.');
    }

    public function destroy(User $parent)
    {
        if (!$parent->isParent()) abort(404);

        $parent->parentProfile()->delete();
        $parent->delete();

        return redirect()->route('admin.parents.index')
            ->with('success', 'Parent supprimé.');
    }
}