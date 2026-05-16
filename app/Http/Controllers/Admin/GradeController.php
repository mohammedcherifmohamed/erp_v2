<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Grade;
use App\Models\Level;
use App\Services\AuditService;

class GradeController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');

        $grades = Grade::with('level')
            ->withCount('classes')
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%"))
            ->when(request('level_id'), fn($q, $v) => $q->where('level_id', $v))
            ->ordered()
            ->paginate(15);

        if (request()->ajax()) {
            return view('grades._table', compact('grades'))->render();
        }

        return view('grades.index', compact('grades'));
    }

    public function create()
    {
        $levels = Level::active()->ordered()->get();
        return view('grades.create', compact('levels'));
    }

    public function store(StoreGradeRequest $request)
    {
        $grade = Grade::create($request->validated());
        $this->auditService->logCreate($grade, $grade->toArray());

        return redirect()->route('admin.grades.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function show(Grade $grade)
    {
        $grade->load(['level', 'classes']);
        return view('grades.show', compact('grade'));
    }

    public function edit(Grade $grade)
    {
        $levels = Level::active()->ordered()->get();
        return view('grades.edit', compact('grade', 'levels'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $old = $grade->toArray();
        $grade->update($request->validated());
        $this->auditService->logUpdate($grade, $old, $grade->toArray());

        return redirect()->route('admin.grades.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Grade $grade)
    {
        if ($grade->classes()->exists()) {
            return back()->with('error', 'Impossible de supprimer la classe avec des classes existantes.');
        }

        $this->auditService->logDelete($grade, $grade->toArray());
        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Classe supprimée avec succès.');
    }
}