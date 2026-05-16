<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;

// Public landing
Route::get('/', [LandingPageController::class, 'home'])->name('home');
Route::get('/courses', [LandingPageController::class, 'courses'])->name('courses');
Route::get('/courses/{classe}', [LandingPageController::class, 'courseDetails'])->name('courses.details');
Route::post('/courses/{classe}/enroll', [LandingPageController::class, 'enroll'])->middleware('auth')->name('courses.enroll');
Route::post('/courses/{course}/enroll-course', [LandingPageController::class, 'enrollCourse'])->middleware('auth')->name('courses.enroll-course');
Route::get('/enrollments/{enrollment}/success', [LandingPageController::class, 'enrollmentSuccess'])->middleware('auth')->name('enrollments.success');
Route::get('/teacher/register', [LandingPageController::class, 'teacherRegister'])->name('teacher.register');
Route::post('/teacher/register', [LandingPageController::class, 'teacherRegisterStore'])->name('teacher.register.store');
Route::get('/teacher/register/success', [LandingPageController::class, 'teacherRegisterSuccess'])->name('teacher.register.success');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/teacher/login', [AuthController::class, 'showTeacherLogin'])->name('teacher.login');
    Route::post('/teacher/login', [AuthController::class, 'login'])->name('teacher.login.submit');
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