<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherContractController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\TeacherWithdrawalController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:is-admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    Route::resource('levels', LevelController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
    Route::resource('courses', CourseController::class);

    Route::resource('enrollments', EnrollmentController::class)->except(['edit', 'update']);
    Route::get('/enrollments/pending', [EnrollmentController::class, 'pending'])->name('enrollments.pending');
    Route::patch('/enrollments/{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::patch('/enrollments/{enrollment}/archive', [EnrollmentController::class, 'archive'])->name('enrollments.archive');

    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('parents', ParentController::class);

    Route::resource('schedules', ScheduleController::class);
    Route::get('/schedules/weekly/{class?}', [ScheduleController::class, 'weekly'])->name('schedules.weekly');

    Route::resource('attendances', AttendanceController::class)->except(['create', 'store']);
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances/store', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/by-course/{course}', [AttendanceController::class, 'byCourse'])->name('attendances.by-course');
    Route::get('/attendances/analytics', [AttendanceController::class, 'analytics'])->name('attendances.analytics');

    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update']);
    Route::post('/invoices/{invoice}/payments', [InvoiceController::class, 'recordPayment'])->name('invoices.payments');
    Route::get('/invoices/overdue', [InvoiceController::class, 'overdue'])->name('invoices.overdue');

    Route::resource('teacher-contracts', TeacherContractController::class);
    Route::resource('teacher-withdrawals', TeacherWithdrawalController::class)->only(['index', 'show']);
    Route::patch('/teacher-withdrawals/{teacherWithdrawal}/approve', [TeacherWithdrawalController::class, 'approve'])->name('teacher-withdrawals.approve');
    Route::patch('/teacher-withdrawals/{teacherWithdrawal}/complete', [TeacherWithdrawalController::class, 'complete'])->name('teacher-withdrawals.complete');
    Route::post('/teacher-withdrawals/{teacherWithdrawal}/reject', [TeacherWithdrawalController::class, 'reject'])->name('teacher-withdrawals.reject');
});