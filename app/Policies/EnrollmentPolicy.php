<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\Classe;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\User;

class AcademicPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, $model): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isTeacher()) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, $model): bool
    {
        return $user->isAdmin();
    }
}

class EnrollmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Enrollment $enrollment): bool
    {
        if ($user->isAdmin()) return true;
        if ($enrollment->student_id === $user->id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }

    public function reject(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Enrollment $enrollment): bool
    {
        return $user->isAdmin();
    }
}

class SchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'teacher', 'student']);
    }

    public function view(User $user, Schedule $schedule): bool
    {
        if ($user->isAdmin()) return true;
        if ($schedule->teacher_id === $user->id) return true;
        if ($schedule->classe?->students()->where('student_id', $user->id)->exists()) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->isAdmin();
    }
}

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isAdmin()) return true;
        if ($attendance->student_id === $user->id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, Attendance $attendance): bool
    {
        return $user->isAdmin();
    }
}

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Invoice $invoice): bool
    {
        if ($user->isAdmin()) return true;
        if ($invoice->student_id === $user->id) return true;
        if ($invoice->parent_id === $user->id) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }
}

class PaymentPolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Payment $payment): bool
    {
        if ($user->isAdmin()) return true;
        if ($payment->invoice->student_id === $user->id) return true;
        if ($payment->invoice->parent_id === $user->id) return true;
        return false;
    }
}

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTeacher() || $user->isStudent();
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->isAdmin()) return true;
        if ($quiz->teacher_id === $user->id) return true;
        if ($user->isStudent() && $quiz->classe?->students()->where('student_id', $user->id)->exists()) return $quiz->is_published;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $quiz->teacher_id === $user->id);
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin() || ($user->isTeacher() && $quiz->teacher_id === $user->id);
    }

    public function publish(User $user, Quiz $quiz): bool
    {
        return $user->isTeacher() && $quiz->teacher_id === $user->id;
    }

    public function submit(User $user, Quiz $quiz): bool
    {
        return $user->isStudent() && $quiz->is_published;
    }
}

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, $announcement): bool
    {
        if ($user->isAdmin()) return true;
        if ($announcement->is_global) return true;
        if ($announcement->author_id === $user->id) return true;
        if ($user->isStudent() && $announcement->class_id && $announcement->classe?->students()->where('student_id', $user->id)->exists()) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isTeacher() || $user->isAdmin();
    }

    public function update(User $user, $announcement): bool
    {
        return $user->isAdmin() || $announcement->author_id === $user->id;
    }

    public function delete(User $user, $announcement): bool
    {
        return $user->isAdmin() || $announcement->author_id === $user->id;
    }
}

class TeacherContractPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, $contract): bool
    {
        return $user->isAdmin();
    }
}

class TeacherWithdrawalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }
}