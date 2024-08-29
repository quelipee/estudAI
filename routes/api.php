<?php

use App\Domains\CourseDomain\API\RequestsAPI;
use App\Domains\UserDomain\UserController\UserController;
use App\Http\Middleware\EnsureHasCourseMiddleware;
use App\Http\Middleware\PreventDuplicateEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('register', [UserController::class,'signUp'])->name('signUp');
    Route::post('login', [UserController::class,'signIn'])->name('signIn');
});


Route::prefix('app')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [UserController::class,'signOut'])->name('signOut');
    Route::post('join-course/{course}', [UserController::class,'joinCourse'])->name('join-course')
        ->middleware(PreventDuplicateEnrollment::class);
    Route::post('leave-course/{course}',[UserController::class,'leaveCourse'])->name('leave-course');
    Route::get('requestChat/{course}',[RequestsAPI::class,'requestChat'])->name('requestChat')
        ->middleware(EnsureHasCourseMiddleware::class);
    Route::get('profile',[UserController::class,'loadUserProfile'])->name('loadUserProfile');
});

Route::post('chat/{course}/topic/{topic}/message',[\App\Domains\ChatDomain\Controllers\ChatController::class,'chatTopic'])->name('sendChat');
