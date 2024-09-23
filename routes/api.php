<?php

use App\Domains\ChatDomain\Controllers\ChatController;
use App\Domains\CourseDomain\CourseController\APICourseController;
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
    Route::get('message_day', [ChatController::class,'message_day'])->name('message_day');
});


Route::prefix('app')->middleware(['auth:sanctum'])->group(function () {
    Route::get('courses', [APICourseController::class, 'index'])->name('courses');

    Route::post('logout', [UserController::class,'signOut'])->name('signOut');
    //TODO NOT APPLY
    Route::get('join-course/{course}', [UserController::class,'joinCourse'])->name('join-course')
        ->middleware(PreventDuplicateEnrollment::class);
    //TODO NOT APLLY
    Route::post('leave-course/{course}',[UserController::class,'leaveCourse'])->name('leave-course');

    Route::post('chat/{course}/topic/{topic}/message',[ChatController::class,'chatTopic'])
        ->name('sendChat')->middleware(EnsureHasCourseMiddleware::class);

    Route::get('profile',[UserController::class,'loadUserProfile'])->name('loadUserProfile');

    Route::get('messageChat/{id}',[ChatController::class, 'requestForMessageChatThisUser'])->name('messageChat');

    Route::get('introduction/{id}',[ChatController::class, 'introductionResponse'])->name('introduction');

    Route::get('your_courses',[APICourseController::class, 'getUserCourseByAuthentication'])->name('yourCourses');

    Route::get('topics/{id}',[APICourseController::class, 'pickUpThreads'])->name('take_all_topics_for_user');

    Route::get('findTopic/{id}',[APICourseController::class, 'takeTopicWithCourse'])->name('find_topic');

    Route::get('findCourse/{id}',[APICourseController::class, 'takeCourse'])->name('find_course');
});
