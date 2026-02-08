<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController; // ✅ ДОДАЈ ОВА
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/assessment/start', [AssessmentController::class, 'start'])->name('assessment.start');
    Route::post('/assessment/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/assessment/{assessment}', [AssessmentController::class, 'show'])->name('assessment.show');

});

require __DIR__.'/auth.php';
