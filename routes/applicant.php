<?php

use App\Http\Controllers\Applicant\ALevelEducationController;
use App\Http\Controllers\Applicant\ApplicantController;
use App\Http\Controllers\Applicant\MotivationController;
use App\Http\Controllers\Applicant\OLevelEducationController;
use App\Http\Controllers\Beneficiary\BeneficiaryController;
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



    Route::get('applicant/review', [ApplicantController::class, 'review'])->name('applicant.application.review');
    // Route::post('applicant/submit', [ApplicantController::class, 'submit'])->name('applicant.application.submit');


 Route::post('/select-scholarship', [ApplicantController::class, 'selectScholarship'])->name('applicant.application.select-scholarship');
    Route::post('/submit', [ApplicantController::class, 'submit'])->name('applicant.application.submit');
    Route::get('/edit', [ApplicantController::class, 'edit'])->name('applicant.application.edit');
    Route::put('/update', [ApplicantController::class, 'update'])->name('applicant.application.update');
    Route::post('/withdraw', [ApplicantController::class, 'withdraw'])->name('applicant.application.withdraw');
     Route::get('applicant/my-application', [ApplicantController::class, 'myApplication'])->name('applicant.my-application');


    // Acceptance Letter
    Route::get('/download-acceptance', [ApplicantController::class, 'downloadAcceptance'])->name('applicant.acceptance.download');





    


    // Acknowledgement Letter
    Route::get('/acknowledgement-letter', [ApplicantController::class, 'acknowledgementShow'])->name('applicant.acknowledgement-letter');
    Route::post('/acknowledgement-letter', [ApplicantController::class, 'submitAcknowledgementLetter'])->name('applicant.acknowledgement-letter.submit');
    Route::get('/download-acknowledgement-letter', [ApplicantController::class, 'downloadAcknowledgementLetter'])->name('applicant.acknowledgement-letter.download');

});
