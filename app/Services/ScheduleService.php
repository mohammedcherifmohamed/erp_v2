<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function create(array $data): Schedule
    {
        $conflicts = $this->checkConflicts($data);

        if ($conflicts->isNotEmpty()) {
            throw new \RuntimeException('Schedule conflict detected: ' . $conflicts->first()->get('message'));
        }

        $schedule = DB::transaction(function () use ($data) {
            $schedule = Schedule::create($data);
            $this->auditService->logCreate($schedule, $data);
            return $schedule;
        });

        return $schedule->load(['course', 'teacher', 'classe']);
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        $conflicts = $this->checkConflicts($data, $schedule->id);

        if ($conflicts->isNotEmpty()) {
            throw new \RuntimeException('Schedule conflict detected: ' . $conflicts->first()->get('message'));
        }

        return DB::transaction(function () use ($schedule, $data) {
            $old = $schedule->toArray();
            $schedule->update($data);
            $this->auditService->logUpdate($schedule, $old, $schedule->toArray());
            return $schedule;
        });
    }

    public function checkConflicts(array $data, ?int $excludeId = null)
    {
        $query = Schedule::where('day_of_week', $data['day_of_week'])
            ->where('is_active', true)
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($q2) use ($data) {
                        $q2->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = collect();

        $teacherConflict = (clone $query)->where('teacher_id', $data['teacher_id'])->first();
        if ($teacherConflict) {
            $conflicts->push(collect([
                'type' => 'teacher',
                'message' => "Teacher is already scheduled during this time period",
                'conflict' => $teacherConflict,
            ]));
        }

        if (isset($data['classroom'])) {
            $roomConflict = (clone $query)->where('classroom', $data['classroom'])->first();
            if ($roomConflict) {
                $conflicts->push(collect([
                    'type' => 'classroom',
                    'message' => "Classroom '{$data['classroom']}' is already booked during this time",
                    'conflict' => $roomConflict,
                ]));
            }
        }

        $classConflict = (clone $query)->where('class_id', $data['class_id'])->first();
        if ($classConflict) {
            $conflicts->push(collect([
                'type' => 'class',
                'message' => "Class already has a lesson scheduled during this time",
                'conflict' => $classConflict,
            ]));
        }

        return $conflicts;
    }

    public function getWeeklySchedule(int $classId)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $schedules = Schedule::with(['course', 'teacher', 'classe'])
            ->where('class_id', $classId)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        $week = collect();
        foreach ($days as $day) {
            $week[$day] = $schedules->get($day, collect());
        }

        return $week;
    }

    public function getTeacherSchedule(int $teacherId)
    {
        return Schedule::with(['course', 'classe.grade.level'])
            ->forTeacher($teacherId)
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
    }

    public function getStudentSchedule(int $classId)
    {
        return $this->getWeeklySchedule($classId);
    }
}