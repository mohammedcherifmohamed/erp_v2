<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:is-student'])->prefix('student')->name('student.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'studentDashboard'])->name('dashboard');

    Route::get('/quizzes', [StudentController::class, 'quizzes'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}/take', [StudentController::class, 'takeQuiz'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [StudentController::class, 'submitQuiz'])->name('quizzes.submit');
    Route::get('/quizzes/{quiz}/results', [StudentController::class, 'quizResults'])->name('quizzes.results');

    Route::get('/schedule', [StudentController::class, 'schedule'])->name('schedule');

    Route::get('/invoices', [StudentController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [StudentController::class, 'invoiceShow'])->name('invoices.show');
});