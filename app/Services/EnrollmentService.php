<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function __construct(
        private readonly AuditService $auditService,
        private readonly InvoiceService $invoiceService,
    ) {}

    public function submit(array $data): Enrollment
    {
        return DB::transaction(function () use ($data) {
            $enrollment = Enrollment::create([
                'student_id' => $data['student_id'],
                'class_id' => $data['class_id'],
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);

            $this->auditService->logCreate($enrollment, $data);

            return $enrollment->load(['student', 'classe']);
        });
    }

    public function approve(Enrollment $enrollment): Enrollment
    {
        return DB::transaction(function () use ($enrollment) {
            $enrollment->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $enrollment->classe->increment('enrolled_count');

            $this->invoiceService->generateForEnrollment($enrollment);
            $this->auditService->logUpdate($enrollment, ['status' => 'pending'], ['status' => 'approved']);

            return $enrollment;
        });
    }

    public function reject(Enrollment $enrollment, string $reason): Enrollment
    {
        return DB::transaction(function () use ($enrollment, $reason) {
            $enrollment->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            $this->auditService->logUpdate($enrollment, ['status' => 'pending'], ['status' => 'rejected']);

            return $enrollment;
        });
    }

    public function archive(Enrollment $enrollment): Enrollment
    {
        $enrollment->update(['status' => 'archived']);
        return $enrollment;
    }

    public function getPendingEnrollments(int $perPage = 15)
    {
        return Enrollment::with(['student.studentProfile', 'classe.grade.level'])
            ->pending()
            ->latest()
            ->paginate($perPage);
    }

    public function getEnrollmentsByClass(int $classId, int $perPage = 15)
    {
        return Enrollment::with(['student.studentProfile'])
            ->where('class_id', $classId)
            ->latest()
            ->paginate($perPage);
    }

    public function getEnrollmentsByStudent(int $studentId, int $perPage = 15)
    {
        return Enrollment::with(['classe.grade.level'])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate($perPage);
    }

    public function getAvailableSeats(Classe $classe): int
    {
        return $classe->capacity - $classe->enrolled_count;
    }

    public function canEnroll(Classe $classe): bool
    {
        return $this->getAvailableSeats($classe) > 0;
    }
}