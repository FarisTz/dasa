<?php

use App\Http\Controllers\ALevelEducationController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\MotivationController;
use App\Http\Controllers\OLevelEducationController;
use Illuminate\Support\Facades\Route;

// Applicant routes
Route::middleware(['auth'])->group(function () {
    // Personal Information Routes
    Route::get('/personal-information', [ApplicantController::class, 'index'])->name('applicant.personal_information');
    Route::post('/personal-information', [ApplicantController::class, 'store'])->name('applicant.personal-information.store');

    // O-Level Education Routes
    Route::get('/applicant/o-level-education', [OLevelEducationController::class, 'index'])->name('applicant.o-level-education');
    Route::post('/applicant/o-level-education', [OLevelEducationController::class, 'store'])->name('applicant.o-level-education.store');
    // A-Level Education Routes
    Route::get('/applicant/a-level-education', [ALevelEducationController::class, 'index'])->name('applicant.a-level-education');
    Route::post('/applicant/a-level-education', [ALevelEducationController::class, 'store'])->name('applicant.a-level-education.store');

     Route::get('applicant/motivations', [MotivationController::class, 'index'])->name('applicant.motivations.index');
    Route::post('applicant/motivations', [MotivationController::class, 'store'])->name('applicant.motivations.store');



    Route::get('/review', [ApplicantController::class, 'review'])->name('applicant.application.review');
    Route::post('/submit', [ApplicantController::class, 'submit'])->name('applicant.application.submit');



});
