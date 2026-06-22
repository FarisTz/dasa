<?php

use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminScholarshipController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AuthRedirectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
     Route::get('/dashboard/statistics', [AuthRedirectController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('/dashboard/chart-data', [AuthRedirectController::class, 'chartData'])->name('dashboard.chart-data');





    // User Management Routes
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');






     // Application Management Routes
    Route::get('/applications', [AdminApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [AdminApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{id}/edit', [AdminApplicationController::class, 'edit'])->name('applications.edit');
    Route::put('/applications/{id}', [AdminApplicationController::class, 'update'])->name('applications.update');
    Route::delete('/applications/{id}', [AdminApplicationController::class, 'destroy'])->name('applications.destroy');
    Route::post('/applications/bulk-action', [AdminApplicationController::class, 'bulkAction'])->name('applications.bulk-action');
    Route::post('/applications/update-status', [AdminApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/review/{id}', [AdminApplicationController::class, 'review'])->name('applications.review');
    Route::get('/applications/export', [AdminApplicationController::class, 'export'])->name('applications.export');






    // Scholarship Management Routes
    Route::get('/scholarships', [AdminScholarshipController::class, 'index'])->name('scholarships.index');
    Route::get('/scholarships/create', [AdminScholarshipController::class, 'create'])->name('scholarships.create');
    Route::post('/scholarships', [AdminScholarshipController::class, 'store'])->name('scholarships.store');
    Route::get('/scholarships/{id}', [AdminScholarshipController::class, 'show'])->name('scholarships.show');
    Route::get('/scholarships/{id}/edit', [AdminScholarshipController::class, 'edit'])->name('scholarships.edit');
    Route::put('/scholarships/{id}', [AdminScholarshipController::class, 'update'])->name('scholarships.update');
    Route::delete('/scholarships/{id}', [AdminScholarshipController::class, 'destroy'])->name('scholarships.destroy');
    Route::get('/scholarships/{id}/toggle-status', [AdminScholarshipController::class, 'toggleStatus'])->name('scholarships.toggle-status');
    Route::post('/scholarships/bulk-action', [AdminScholarshipController::class, 'bulkAction'])->name('scholarships.bulk-action');
    Route::get('/scholarships/export', [AdminScholarshipController::class, 'export'])->name('scholarships.export');



    Route::put('/applications/{id}/acknowledgement', [AdminApplicationController::class, 'updateAcknowledgement'])->name('applications.acknowledgement.update');
});
