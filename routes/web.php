<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Public landing
Route::get('/', [LandingPageController::class, 'home'])->name('home');
Route::get('/courses', [LandingPageController::class, 'courses'])->name('courses');
Route::get('/courses/{classe}', [LandingPageController::class, 'courseDetails'])->name('courses.details');
Route::get('/teacher/register', [LandingPageController::class, 'teacherRegister'])->name('teacher.register');
Route::post('/teacher/register', [LandingPageController::class, 'teacherRegisterStore'])->name('teacher.register.store');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Role-specific route files
require __DIR__ . '/admin.php';
require __DIR__ . '/teacher.php';
require __DIR__ . '/student.php';
require __DIR__ . '/parent.php';