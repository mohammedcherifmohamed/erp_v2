<?php

namespace App\Listeners;

use App\Events\TeacherApplied;
use App\Notifications\TeacherApplicationReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTeacherApplicationConfirmation implements ShouldQueue
{
    public function handle(TeacherApplied $event): void
    {
        $event->teacher->notify(new TeacherApplicationReceived($event->teacher));
    }
}
