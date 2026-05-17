<?php

namespace App\Listeners;

use App\Events\EnrollmentSubmitted;
use App\Notifications\EnrollmentConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnrollmentConfirmation implements ShouldQueue
{
    public function handle(EnrollmentSubmitted $event): void
    {
        $enrollment = $event->enrollment->load(['student', 'classe', 'course']);
        $enrollment->student->notify(new EnrollmentConfirmation($enrollment));
    }
}
