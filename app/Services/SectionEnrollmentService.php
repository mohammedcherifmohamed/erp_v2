<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\SectionEnrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SectionEnrollmentService
{
    public function __construct(
        private readonly AuditService $auditService,
        private readonly InvoiceService $invoiceService,
    ) {}

    public function submit(User $student, Classe $section, ?int $courseId = null): SectionEnrollment
    {
        return DB::transaction(function () use ($student, $section) {
            $enrollment = SectionEnrollment::create([
                'student_id' => $student->id,
                'section_id' => $section->id,
                'bundle_price_paid' => $section->bundle_discounted_price ?? $section->bundle_price,
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);

            $this->auditService->logCreate($enrollment, [
                'student_id' => $student->id,
                'section_id' => $section->id,
                'type' => 'section_bundle',
            ]);

            return $enrollment->load(['student', 'section.courses']);
        });
    }

    public function approve(SectionEnrollment $enrollment, ?string $startDate = null, ?string $endDate = null): SectionEnrollment
    {
        return DB::transaction(function () use ($enrollment, $startDate, $endDate) {
            $enrollment->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'start_date' => $startDate ?? now()->format('Y-m-d'),
                'end_date' => $endDate ?? now()->addYear()->format('Y-m-d'),
            ]);

            $enrollment->section->increment('enrolled_count');

            $courses = $enrollment->section->courses;
            foreach ($courses as $course) {
                $existingEnrollment = Enrollment::where('student_id', $enrollment->student_id)
                    ->where('class_id', $enrollment->section_id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$existingEnrollment) {
                    Enrollment::create([
                        'student_id' => $enrollment->student_id,
                        'class_id' => $enrollment->section_id,
                        'course_id' => $course->id,
                        'status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'start_date' => $enrollment->start_date,
                        'end_date' => $enrollment->end_date,
                    ]);

                    $course->increment('enrolled_count');
                }
            }

            $enrollment->load(['section.courses']);
            $this->invoiceService->generateForSectionEnrollment($enrollment);
            $this->auditService->logUpdate($enrollment, ['status' => 'pending'], ['status' => 'approved']);

            return $enrollment;
        });
    }

    public function reject(SectionEnrollment $enrollment, string $reason): SectionEnrollment
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

    public function archive(SectionEnrollment $enrollment): SectionEnrollment
    {
        $enrollment->update(['status' => 'archived']);
        return $enrollment;
    }

    public function getRemainingBundleSeats(Classe $section): int
    {
        $approvedCount = SectionEnrollment::where('section_id', $section->id)
            ->where('status', 'approved')
            ->count();

        return $section->capacity - $section->enrolled_count - $approvedCount;
    }

    public function canEnrollBundle(Classe $section): bool
    {
        return $this->getRemainingBundleSeats($section) > 0;
    }
}
