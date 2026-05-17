<?php

namespace App\Listeners;

use App\Events\EnrollmentRejected;
use App\Notifications\EnrollmentRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnrollmentRejectionNotification implements ShouldQueue
{
    public function handle(EnrollmentRejected $event): void
    {
        $enrollment = $event->enrollment->load(['student', 'classe', 'course']);
        $enrollment->student->notify(new EnrollmentRejectedNotification($enrollment));
    }
}
