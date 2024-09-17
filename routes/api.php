<?php

use App\Domains\ChatDomain\Controllers\ChatController;
use App\Domains\UserDomain\UserController\UserController;
use App\Http\Middleware\EnsureHasCourseMiddleware;
use App\Http\Middleware\PreventDuplicateEnrollment;
use GeminiAPI\Enums\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\Topic;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('register', [UserController::class,'signUp'])->name('signUp');
    Route::post('login', [UserController::class,'signIn'])->name('signIn');
    Route::get('courses',function(){
        //TODO ADJUSTS AFTER
        $courses = Course::all();
        return response()->json(
            ['courses' => $courses]
        );
    });
});


Route::prefix('app')->middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [UserController::class,'signOut'])->name('signOut');
    //TODO NOT APPLY
    Route::get('join-course/{course}', [UserController::class,'joinCourse'])->name('join-course')
        ->middleware(PreventDuplicateEnrollment::class);
    //TODO NOT APLLY
    Route::post('leave-course/{course}',[UserController::class,'leaveCourse'])->name('leave-course');

    Route::post('chat/{course}/topic/{topic}/message',[ChatController::class,'chatTopic'])
        ->name('sendChat')->middleware(EnsureHasCourseMiddleware::class);

    Route::get('profile',[UserController::class,'loadUserProfile'])->name('loadUserProfile');

    Route::get('messageChat/{id}',function($id){
        $user = Auth::user();
        return $user->load(['messageHistory' => function($query) use ($id,$user){
            $query->where('role', Role::Model->name)
                ->where('topic_id',$id)->where('user_id',$user->id);
        }]);
    });

    Route::get('your_courses',function(){
        $user = Auth::user();
        return response()->json([
            'courses' => $user->courses,
        ]);
    })->name('yourCourses');
});

//TODO ADJUSTS LATER
Route::get('topics/{id}',function($id){
    $topics = Topic::query()->where('course_id',$id)->get();
    return $topics;
})->name('courses_topics');

Route::get('findTopic/{id}',function($id){
   $topic = Topic::query()->where('id',$id)->first();
   return $topic;
});

Route::get('findCourse/{id}',function($id){
    return Course::query()->where('id',$id)->first();
})->name('find_course');
