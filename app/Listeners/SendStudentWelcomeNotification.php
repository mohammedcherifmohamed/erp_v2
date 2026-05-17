<?php

namespace App\Listeners;

use App\Events\StudentRegistered;
use App\Notifications\StudentWelcome;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendStudentWelcomeNotification implements ShouldQueue
{
    public function handle(StudentRegistered $event): void
    {
        $event->student->notify(new StudentWelcome($event->student));
    }
}
