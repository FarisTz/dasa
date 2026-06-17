<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthRedirectController;



Route::get('/',[AuthRedirectController::class,'redirect']);
Route::get('/dashboard', [AuthRedirectController::class,'dashboardRedirect'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/logout', [AuthRedirectController::class,'logout'])->middleware(['auth'])->name('logout');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
require __DIR__.'/applicant.php';
require __DIR__.'/admin.php';
