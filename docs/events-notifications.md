# Events & Notifications

This document describes the event-driven architecture and notification system used in the School ERP + LMS platform.

## Overview

The platform uses Laravel's event system to decouple domain actions from their side effects (primarily email notifications). When a significant action occurs (e.g., enrollment approval), a domain event is dispatched, which triggers one or more listeners.

## Event Flow

```
Domain Action → Event Dispatched → Listener Triggered → Notification Sent
```

## Events

All events are located in `app/Events/`.

### StudentRegistered

**Dispatched when:** A student self-registers via the `/register` route.

**Payload:** `User $user`

**Trigger location:** `AuthController::register()`

### TeacherApplied

**Dispatched when:** A teacher submits a public application via `/teacher/register`.

**Payload:** `User $teacher`

**Trigger location:** `LandingPageController::teacherRegisterStore()`

### EnrollmentSubmitted

**Dispatched when:** A new enrollment is created with `pending` status.

**Payload:** `Enrollment $enrollment`

**Trigger location:** `Enrollment::booted()` (model event — dispatched automatically on creation)

### EnrollmentApproved

**Dispatched when:** An enrollment's status changes to `approved`.

**Payload:** `Enrollment $enrollment`

**Trigger location:** `Enrollment::booted()` (model event — dispatched automatically on update when status changes)

### EnrollmentRejected

**Dispatched when:** An enrollment's status changes to `rejected`.

**Payload:** `Enrollment $enrollment`

**Trigger location:** `Enrollment::booted()` (model event — dispatched automatically on update when status changes)

### AccountCreatedByAdmin

**Dispatched when:** An admin creates a new user account (teacher, student, or parent) from the admin panel.

**Payload:** `User $user`

**Trigger location:** Admin controllers (StudentController, TeacherController, ParentController)

## Listeners

All listeners are located in `app/Listeners/`. Each listener handles one event and sends the appropriate notification.

| Listener                             | Event                  | Action                                   |
|--------------------------------------|------------------------|------------------------------------------|
| `SendStudentWelcomeNotification`     | `StudentRegistered`    | Sends welcome email to new student       |
| `SendTeacherApplicationConfirmation` | `TeacherApplied`       | Sends confirmation to teacher applicant  |
| `SendEnrollmentConfirmation`         | `EnrollmentSubmitted`  | Sends enrollment confirmation to student |
| `SendEnrollmentApprovalNotification` | `EnrollmentApproved`   | Notifies student of enrollment approval  |
| `SendEnrollmentRejectionNotification`| `EnrollmentRejected`   | Notifies student of enrollment rejection |
| `SendAccountSetupInvitation`         | `AccountCreatedByAdmin`| Sends password setup invitation link     |

## Event-Listener Registration

All event-listener mappings are registered in `app/Providers/EventServiceProvider.php`:

```php
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
```

## Notifications

All notification classes are located in `app/Notifications/`. They use Laravel's `Notifiable` trait on the `User` model.

### StudentWelcome

**Sent to:** Newly registered students
**Channel:** Mail
**Content:** Welcome message with platform information

### TeacherApplicationReceived

**Sent to:** Teacher who submitted an application
**Channel:** Mail
**Content:** Application confirmation and next steps

### EnrollmentConfirmation

**Sent to:** Student who submitted an enrollment request
**Channel:** Mail
**Content:** Enrollment details, pending status notification

### EnrollmentApprovedNotification

**Sent to:** Student whose enrollment was approved
**Channel:** Mail
**Content:** Approval confirmation, class details, start date

### EnrollmentRejectedNotification

**Sent to:** Student whose enrollment was rejected
**Channel:** Mail
**Content:** Rejection notice with reason

### AccountSetupInvitation

**Sent to:** Users whose accounts were created by an admin
**Channel:** Mail
**Content:** Password setup link (tokenized URL)

## Model-Level Event Dispatching

The `Enrollment` model dispatches events automatically using Laravel's model events in the `booted()` method:

```php
protected static function booted(): void
{
    static::created(function (Enrollment $enrollment) {
        if ($enrollment->status === 'pending') {
            event(new EnrollmentSubmitted($enrollment));
        }
    });

    static::updated(function (Enrollment $enrollment) {
        if ($enrollment->wasChanged('status')) {
            match ($enrollment->status) {
                'approved' => event(new EnrollmentApproved($enrollment)),
                'rejected' => event(new EnrollmentRejected($enrollment)),
                default => null,
            };
        }
    });
}
```

This ensures notifications are sent regardless of which controller or service triggers the status change.

## Mail Configuration

By default, the application uses the `log` mail driver, which writes emails to the Laravel log file instead of sending them. This is ideal for development.

To configure actual email sending, update the `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Supported mailers: `smtp`, `ses`, `mailgun`, `postmark`, `sendmail`, `log`.

## Queue Configuration

Notifications can be queued for asynchronous delivery. The application uses the `database` queue driver by default.

To process queued notifications:

```bash
php artisan queue:work
```

For production, consider using a process manager like Supervisor to keep the queue worker running:

```ini
[program:erp-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/erp_v2/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/erp-queue-worker.log
```
