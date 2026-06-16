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
    Route::get('/o-level', [OLevelEducationController::class, 'index'])->name('applicant.o_level');
    Route::get('/o-level/create', [OLevelEducationController::class, 'create'])->name('applicant.o-level.create');
    Route::post('/o-level', [OLevelEducationController::class, 'store'])->name('applicant.o-level.store');
    Route::get('/o-level/edit', [OLevelEducationController::class, 'edit'])->name('applicant.o-level.edit');
    Route::put('/o-level/{id}', [OLevelEducationController::class, 'update'])->name('applicant.o-level.update');

    // A-Level Education Routes
    Route::get('/a-level', [ALevelEducationController::class, 'index'])->name('applicant.a_level');
    Route::get('/a-level/create', [ALevelEducationController::class, 'create'])->name('applicant.a-level.create');
    Route::post('/a-level', [ALevelEducationController::class, 'store'])->name('applicant.a-level.store');
    Route::get('/a-level/edit', [ALevelEducationController::class, 'edit'])->name('applicant.a-level.edit');
    Route::put('/a-level/{id}', [ALevelEducationController::class, 'update'])->name('applicant.a-level.update');
});
