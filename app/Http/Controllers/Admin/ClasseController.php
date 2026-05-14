<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Models\Classe;
use App\Models\Grade;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Storage;

class ClasseController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $classes = Classe::with(['grade.level', 'homeroomTeacher'])
            ->withCount(['enrollments', 'courses'])
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('section', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('classes._table', compact('classes'))->render();
        }

        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        $grades = Grade::with('level')->active()->ordered()->get();
        $teachers = User::byRole('teacher')->get();
        return view('classes.create', compact('grades', 'teachers'));
    }

    public function store(StoreClasseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('classes', 'public');
        }

        $classe = Classe::create($data);
        $this->auditService->logCreate($classe, $classe->toArray());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(Classe $classe)
    {
        $classe->load([
            'grade.level',
            'homeroomTeacher',
            'courses.teacher',
            'enrollments.student.studentProfile',
            'schedules',
        ]);

        return view('classes.show', compact('classe'));
    }

    public function edit(Classe $classe)
    {
        $grades = Grade::with('level')->active()->ordered()->get();
        $teachers = User::byRole('teacher')->get();
        return view('classes.edit', compact('classe', 'grades', 'teachers'));
    }

    public function update(UpdateClasseRequest $request, Classe $classe)
    {
        $old = $classe->toArray();
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($classe->image) {
                Storage::disk('public')->delete($classe->image);
            }
            $data['image'] = $request->file('image')->store('classes', 'public');
        }

        $classe->update($data);
        $this->auditService->logUpdate($classe, $old, $classe->toArray());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(Classe $classe)
    {
        if ($classe->enrollments()->where('status', 'approved')->exists()) {
            return back()->with('error', 'Cannot delete class with active enrollments.');
        }

        if ($classe->image) {
            Storage::disk('public')->delete($classe->image);
        }

        $this->auditService->logDelete($classe, $classe->toArray());
        $classe->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}