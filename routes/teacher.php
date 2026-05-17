<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Teacher\AnnouncementController;
use App\Http\Controllers\Teacher\QuizController;
use App\Http\Controllers\Teacher\TeacherAttendanceController;
use App\Http\Controllers\Teacher\TeacherWithdrawalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:is-teacher'])->prefix('teacher')->name('teacher.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'teacherDashboard'])->name('dashboard');

    Route::resource('quizzes', QuizController::class);
    Route::patch('/quizzes/{quiz}/publish', [QuizController::class, 'publish'])->name('quizzes.publish');
    Route::get('/quizzes/{quiz}/correct', [QuizController::class, 'correct'])->name('quizzes.correct');
    Route::post('/quizzes/{quiz}/submit-correction', [QuizController::class, 'submitCorrection'])->name('quizzes.submit-correction');

    Route::resource('announcements', AnnouncementController::class);

    Route::get('/attendances', [TeacherAttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/mark/{course}', [TeacherAttendanceController::class, 'mark'])->name('attendances.mark');
    Route::post('/attendances/store', [TeacherAttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/history/{course}', [TeacherAttendanceController::class, 'history'])->name('attendances.history');

    Route::get('/withdrawals', [TeacherWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::post('/withdrawals', [TeacherWithdrawalController::class, 'store'])->name('withdrawals.store');

    Route::get('/schedule', [TeacherAttendanceController::class, 'schedule'])->name('schedule');
});