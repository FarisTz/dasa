<?php

use App\Http\Controllers\AuthRedirectController;
use App\Http\Controllers\Coordinator\CoordinatorController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'coordinator'])->prefix('coordinator')->name('coordinator.')->group(function () {


    Route::get('/dashboard/statistics', [AuthRedirectController::class, 'statistics'])->name('dashboard.statistics');
    Route::get('/dashboard/chart-data', [AuthRedirectController::class, 'chartData'])->name('dashboard.chart-data');



    Route::get('/scholarships', [CoordinatorController::class, 'scholarships'])->name('scholarships.index');
    Route::get('/scholarships/{id}', [CoordinatorController::class, 'showScholarship'])->name('scholarships.show');



    Route::get('/applications', [CoordinatorController::class, 'applications'])->name('applications.index');
    Route::get('/applications/{id}', [CoordinatorController::class, 'showApplication'])->name('applications.show');




     //report management routes
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/application', [App\Http\Controllers\Admin\ReportController::class, 'applicationReport'])->name('reports.application');
    Route::get('/reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'beneficiaryFinancialReport'])->name('reports.financial');
    Route::get('/reports/academic', [App\Http\Controllers\Admin\ReportController::class, 'academicPerformanceReport'])->name('reports.academic');
    Route::get('/reports/utilization', [App\Http\Controllers\Admin\ReportController::class, 'scholarshipUtilizationReport'])->name('reports.utilization');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'exportCSV'])->name('reports.export');
});
?>
