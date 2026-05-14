<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\Course;
use App\Models\Schedule;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly ScheduleService $scheduleService,
    ) {}

    public function index()
    {
        $query = request('query');
        $schedules = Schedule::with(['course', 'classe.grade.level', 'teacher'])
            ->when($query, fn($q) => $q->where('classroom', 'like', "%{$query}%")
                ->orWhere('day_of_week', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('schedules._table', compact('schedules'))->render();
        }

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        $courses = Course::with('classe.grade.level')->active()->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('schedules.create', compact('courses', 'days'));
    }

    public function store(StoreScheduleRequest $request)
    {
        try {
            $schedule = $this->scheduleService->create($request->validated());
            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule created successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['course', 'classe.grade.level', 'teacher']);
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $courses = Course::with('classe')->active()->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return view('schedules.edit', compact('schedule', 'courses', 'days'));
    }

    public function update(StoreScheduleRequest $request, Schedule $schedule)
    {
        try {
            $this->scheduleService->update($schedule, $request->validated());
            return redirect()->route('admin.schedules.index')
                ->with('success', 'Schedule updated successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    public function weekly($classId = null)
    {
        if ($classId) {
            $week = $this->scheduleService->getWeeklySchedule($classId);
            $selectedClass = \App\Models\Classe::with('grade.level')->findOrFail($classId);
            $classes = \App\Models\Classe::with('grade.level')->active()->get();
            return view('schedules.weekly', compact('week', 'selectedClass', 'classes'));
        }

        $classes = \App\Models\Classe::with('grade.level')->active()->get();
        return view('schedules.weekly', compact('classes'));
    }
}