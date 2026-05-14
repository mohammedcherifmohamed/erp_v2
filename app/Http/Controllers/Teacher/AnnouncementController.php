<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Models\Announcement;
use App\Models\Classe;
use App\Services\AuditService;

class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $announcements = Announcement::with('classe')
            ->where('author_id', auth()->id())
            ->when($query, fn($q) => $q->where('title', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('announcements._table', compact('announcements'))->render();
        }

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        $classes = Classe::whereHas('courses', fn($q) => $q->where('teacher_id', auth()->id()))
            ->active()
            ->get();

        return view('announcements.create', compact('classes'));
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $data = $request->validated();
        $data['author_id'] = auth()->id();
        $data['published_at'] = now();

        $announcement = Announcement::create($data);
        $this->auditService->logCreate($announcement, $announcement->toArray());

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    public function show(Announcement $announcement)
    {
        if ($announcement->author_id !== auth()->id()) abort(403);
        $announcement->load(['author', 'classe']);
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        if ($announcement->author_id !== auth()->id()) abort(403);

        $classes = Classe::whereHas('courses', fn($q) => $q->where('teacher_id', auth()->id()))
            ->active()
            ->get();

        return view('announcements.edit', compact('announcement', 'classes'));
    }

    public function update(StoreAnnouncementRequest $request, Announcement $announcement)
    {
        if ($announcement->author_id !== auth()->id()) abort(403);

        $old = $announcement->toArray();
        $announcement->update($request->validated());
        $this->auditService->logUpdate($announcement, $old, $announcement->toArray());

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->author_id !== auth()->id()) abort(403);

        $announcement->delete();
        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement deleted.');
    }
}