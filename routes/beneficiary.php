<?php


use App\Http\Controllers\AuthRedirectController;
use App\Http\Controllers\Beneficiary\BeneficiaryController;
use App\Http\Controllers\Beneficiary\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Beneficiary\ResultController;

// Beneficiary Payment Routes
Route::middleware(['auth', 'beneficiary'])->prefix('beneficiary')->name('beneficiary.')->group(function () {
    // Route::get('/payments', [App\Http\Controllers\Beneficiary\PaymentController::class, 'index'])->name('payments.index');
    // Route::get('/payments/{id}/sign', [App\Http\Controllers\Beneficiary\PaymentController::class, 'sign'])->name('payments.sign');
    // Route::post('/payments/submit-sign', [App\Http\Controllers\Beneficiary\PaymentController::class, 'submitSign'])->name('payments.submit-sign');
    // Route::get('/payments/{id}/resend-otp', [App\Http\Controllers\Beneficiary\PaymentController::class, 'resendOTP'])->name('payments.resend-otp');
    // Route::get('/payments/{id}/show', [App\Http\Controllers\Beneficiary\PaymentController::class, 'show'])->name('payments.show');










    // Support

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}/sign', [PaymentController::class, 'sign'])->name('payments.sign');
    Route::post('/payments/submit-sign', [PaymentController::class, 'submitSign'])->name('payments.submit-sign');
    Route::get('/payments/{id}/resend-otp', [PaymentController::class, 'resendOTP'])->name('payments.resend-otp');
    Route::get('/payments/{id}/show', [PaymentController::class, 'show'])->name('payments.show');






   





     Route::resource('results', ResultController::class);
    Route::get('results/{id}/download', [ResultController::class, 'download'])->name('results.download');
});








?>
