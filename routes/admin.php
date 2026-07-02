<?php

use App\Http\Controllers\Admin\AdminAcknowledgementController;
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



    // Acknowledgement Management
    Route::get('/acknowledgement', [AdminAcknowledgementController::class, 'index'])->name('acknowledgement.index');
    Route::get('/acknowledgement/template', [AdminAcknowledgementController::class, 'template'])->name('acknowledgement.template');
    Route::post('/acknowledgement/template/upload', [AdminAcknowledgementController::class, 'uploadTemplate'])->name('acknowledgement.upload-template');
    Route::delete('/acknowledgement/template/delete', [AdminAcknowledgementController::class, 'deleteTemplate'])->name('acknowledgement.delete-template');
    Route::get('/acknowledgement/template/download', [AdminAcknowledgementController::class, 'downloadTemplate'])->name('acknowledgement.download-template');

    // Individual Actions
    Route::put('/acknowledgement/{id}/user-type', [AdminAcknowledgementController::class, 'updateUserType'])->name('acknowledgement.update-user-type');
    Route::get('/acknowledgement/{id}/view-letter', [AdminAcknowledgementController::class, 'viewLetter'])->name('acknowledgement.view-letter');

    // Bulk Actions
    Route::post('/acknowledgement/bulk-update-type', [AdminAcknowledgementController::class, 'bulkUpdateUserType'])->name('acknowledgement.bulk-update-type');
    Route::post('/acknowledgement/bulk-approve', [AdminAcknowledgementController::class, 'bulkApprove'])->name('acknowledgement.bulk-approve');
    Route::post('/acknowledgement/bulk-reject', [AdminAcknowledgementController::class, 'bulkReject'])->name('acknowledgement.bulk-reject');


    // Installment Management Routes
    Route::resource('installments', App\Http\Controllers\Admin\InstallmentController::class);
    Route::get('/installments/{id}/toggle-status', [App\Http\Controllers\Admin\InstallmentController::class, 'toggleStatus'])->name('installments.toggle-status');
    Route::post('/installments/{id}/assign-student', [App\Http\Controllers\Admin\InstallmentController::class, 'assignStudent'])->name('installments.assign-student');
});
