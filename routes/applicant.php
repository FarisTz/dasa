<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\OLevelEducationController;
use App\Http\Controllers\ALevelEducationController;

// Applicant routes
Route::middleware(['auth'])->group(function () {
    // Personal Information Routes
    Route::get('/personal-information', [ApplicantController::class, 'create'])->name('applicant.personal_information');
    Route::get('/personal-information/create', [ApplicantController::class, 'create'])->name('applicant.personal-information.create');
    Route::post('/personal-information', [ApplicantController::class, 'store'])->name('applicant.personal-information.store');
    Route::get('/personal-information/edit', [ApplicantController::class, 'edit'])->name('applicant.personal-information.edit');
    Route::put('/personal-information/{id}', [ApplicantController::class, 'update'])->name('applicant.personal-information.update');

    // O-Level Education Routes
    Route::get('/applicant/o-level-education', [OLevelEducationController::class, 'index'])->name('applicant.o-level-education');
    Route::post('/applicant/o-level-education', [OLevelEducationController::class, 'store'])->name('applicant.o-level-education.store');
    // A-Level Education Routes
    Route::get('/applicant/a-level-education', [ALevelEducationController::class, 'index'])->name('applicant.a-level-education');
    Route::post('/applicant/a-level-education', [ALevelEducationController::class, 'store'])->name('applicant.a-level-education.store');
});
