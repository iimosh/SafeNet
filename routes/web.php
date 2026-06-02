<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return match(auth()->user()->role) {
            'admin'  => redirect('/admin'),
            'parent' => redirect()->route('parent.dashboard'),
            default  => redirect()->route('dashboard'),
        };
    }
    return view('welcome');
})->name('home');

Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/recommendations', 'recommendations')->middleware(['auth', 'verified'])->name('recommendations');

Route::get('/dashboard', function () {
    abort_if(auth()->user()->isParent(), 403);
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment.index');
    Route::get('/assessment/start', [AssessmentController::class, 'start'])->name('assessment.start');
    Route::post('/assessment/submit', [AssessmentController::class, 'submit'])->name('assessment.submit');
    Route::get('/assessment/{assessment}', [AssessmentController::class, 'show'])->name('assessment.show');
    Route::get('/assessment/{assessment}/report', [App\Http\Controllers\ReportController::class, 'generate'])->name('assessment.report');

    Route::get('/questionnaires', [App\Http\Controllers\QuestionnaireController::class, 'index'])->name('questionnaires.index');

    Route::post('/parent/add-child', [App\Http\Controllers\ParentController::class, 'addChild'])
        ->middleware('throttle:invitations')
        ->name('parent.add-child');
    Route::delete('/parent/remove-child/{child}', [App\Http\Controllers\ParentController::class, 'removeChild'])->name('parent.remove-child');
    Route::delete('/parent/invitation/{invitation}', [App\Http\Controllers\ParentController::class, 'cancelInvitation'])->name('parent.invitation.cancel');

    Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.show');
    Route::post('/invitation/{token}/accept', [InvitationController::class, 'accept'])->name('invitation.accept');
    Route::delete('/invitation/{token}', [InvitationController::class, 'decline'])->name('invitation.decline');

    Route::get('/parent/dashboard', function () {
        abort_if(!auth()->user()->isParent(), 403);
        return view('parent.dashboard');
    })->name('parent.dashboard');
});

require __DIR__.'/auth.php';
