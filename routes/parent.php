<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Parent\ParentDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:is-parent'])->prefix('parent')->name('parent.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'parentDashboard'])->name('dashboard');

    Route::get('/children', [ParentDashboardController::class, 'children'])->name('children');
    Route::get('/children/{student}/invoices', [ParentDashboardController::class, 'childInvoices'])->name('children.invoices');
    Route::get('/children/invoices/{invoice}', [ParentDashboardController::class, 'childInvoiceShow'])->name('children.invoices.show');
    Route::get('/children/{student}/schedule', [ParentDashboardController::class, 'childSchedule'])->name('children.schedule');
});