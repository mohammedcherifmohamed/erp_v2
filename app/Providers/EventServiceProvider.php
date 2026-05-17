<?php

namespace App\Providers;

use App\Events\AccountCreatedByAdmin;
use App\Events\EnrollmentApproved;
use App\Events\EnrollmentRejected;
use App\Events\EnrollmentSubmitted;
use App\Events\StudentRegistered;
use App\Events\TeacherApplied;
use App\Listeners\SendAccountSetupInvitation;
use App\Listeners\SendEnrollmentApprovalNotification;
use App\Listeners\SendEnrollmentConfirmation;
use App\Listeners\SendEnrollmentRejectionNotification;
use App\Listeners\SendStudentWelcomeNotification;
use App\Listeners\SendTeacherApplicationConfirmation;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StudentRegistered::class => [
            SendStudentWelcomeNotification::class,
        ],
        TeacherApplied::class => [
            SendTeacherApplicationConfirmation::class,
        ],
        EnrollmentSubmitted::class => [
            SendEnrollmentConfirmation::class,
        ],
        EnrollmentApproved::class => [
            SendEnrollmentApprovalNotification::class,
        ],
        EnrollmentRejected::class => [
            SendEnrollmentRejectionNotification::class,
        ],
        AccountCreatedByAdmin::class => [
            SendAccountSetupInvitation::class,
        ],
    ];

    public function shouldBeDiscoverable(): bool
    {
        return false;
    }
}
