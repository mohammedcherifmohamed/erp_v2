<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Models\Level;
use App\Services\AuditService;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $levels = Level::withCount('grades')
            ->when($query, fn($q) => $q->where('name', 'like', "%{$query}%")
                ->orWhere('name_ar', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%"))
            ->ordered()
            ->paginate(15);

        if (request()->ajax()) {
            return view('levels._table', compact('levels'))->render();
        }

        return view('levels.index', compact('levels'));
    }

    public function create()
    {
        return view('levels.create');
    }

    public function store(StoreLevelRequest $request)
    {
        $level = Level::create($request->validated());
        $this->auditService->logCreate($level, $level->toArray());

        return redirect()->route('admin.levels.index')
            ->with('success', 'Niveau créé avec succès.');
    }

    public function show(Level $level)
    {
        $level->load(['grades.classes']);
        return view('levels.show', compact('level'));
    }

    public function edit(Level $level)
    {
        return view('levels.edit', compact('level'));
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $old = $level->toArray();
        $level->update($request->validated());
        $this->auditService->logUpdate($level, $old, $level->toArray());

        return redirect()->route('admin.levels.index')
            ->with('success', 'Niveau mis à jour avec succès.');
    }

    public function destroy(Level $level)
    {
        if ($level->grades()->exists()) {
            return back()->with('error', 'Impossible de supprimer le niveau avec des classes existantes.');
        }

        $this->auditService->logDelete($level, $level->toArray());
        $level->delete();

        return redirect()->route('admin.levels.index')
            ->with('success', 'Niveau supprimé avec succès.');
    }
}