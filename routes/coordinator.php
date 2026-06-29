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


});
?>
