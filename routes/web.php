<?php

use App\Http\Controllers\AuthRedirectController;
use App\Http\Controllers\Beneficiary\BeneficiaryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/',[AuthRedirectController::class,'redirect']);
Route::get('/dashboard', [AuthRedirectController::class,'dashboardRedirect'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/logout', [AuthRedirectController::class,'logout'])->middleware(['auth'])->name('logout');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');




  Route::get('/support', [BeneficiaryController::class, 'index'])->name('support');
    Route::post('/support', [BeneficiaryController::class, 'store'])->name('support.store');
    Route::get('/support/{id}', [BeneficiaryController::class, 'show'])->name('support.show');
    Route::post('/support/{id}/reply', [BeneficiaryController::class, 'reply'])->name('support.reply');
    Route::get('/support/{id}/close', [BeneficiaryController::class, 'close'])->name('support.close');

});



require __DIR__.'/auth.php';
require __DIR__.'/applicant.php';
require __DIR__.'/admin.php';
require __DIR__.'/coordinator.php';
require __DIR__.'/beneficiary.php';
