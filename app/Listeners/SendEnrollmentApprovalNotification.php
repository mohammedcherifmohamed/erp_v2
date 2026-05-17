<?php

namespace App\Listeners;

use App\Events\EnrollmentApproved;
use App\Notifications\EnrollmentApprovedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnrollmentApprovalNotification implements ShouldQueue
{
    public function handle(EnrollmentApproved $event): void
    {
        $enrollment = $event->enrollment->load([
            'student.studentProfile.parent',
            'classe.grade.level',
            'classe.courses.teacher',
            'course',
        ]);

        $enrollment->student->notify(new EnrollmentApprovedNotification($enrollment));

        $parent = $enrollment->student->studentProfile?->parent;
        if ($parent) {
            $parent->notify(new EnrollmentApprovedNotification($enrollment));
        }
    }
}
