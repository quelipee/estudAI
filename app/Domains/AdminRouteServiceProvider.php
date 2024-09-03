<?php

namespace App\Domains;

use App\Domains\AdmDomains\AdmController;
use App\Domains\CourseDomain\CourseController\CourseController;
use App\Domains\CourseDomain\Enums\Category;
use App\Http\Middleware\isAdminMiddleware;
use App\Models\Course;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminRouteServiceProvider extends RouteServiceProvider
{
    public function map() : void
    {
        Route::prefix('/')->middleware(['web','auth:sanctum', isAdminMiddleware::class])->group(function () {

            Route::get('/',function (){
                $courses = \App\Models\Course::orderBy('updated_at','DESC')->get();

                return view('dashboard',compact('courses'));
            })->name('dashboard');

            Route::get('courses',[CourseController::class,'courses'])
                ->name('index');

            Route::get('edit-course/{course}',function (Course $course){
                $categories = Category::cases();
                return view('edit',compact('course','categories'));
            })->name('edit');

            Route::put('update-course/{course}',[CourseController::class,'updateCourse'])
                ->name('courses.update');

            Route::get('new-course',function (){
                $categories = Category::cases();
                return view('create',compact('categories'));
            })->name('course.create');

            Route::post('new-course', [CourseController::class,'newCourses'])
                ->name('course.store');

            Route::post('add-topic',[CourseController::class,'insertTopicsInCourses'])
                ->name('topics.store');

            Route::delete('delete-topic/{topic}',[
                CourseController::class,'deleteTopicsInCourses'])
                ->name('topic.delete');

            Route::delete('delete-course/{course}',[
                CourseController::class,'deleteCourse'])
                ->name('courses.destroy');

            Route::get('logout', [AdmController::class,'signOut'])
                ->name('admin.logout');
        });

        Route::prefix('/')->middleware(['web','guest:sanctum'])->group(function () {
            Route::get('login',function (){
                return view('signIn');
            })->name('login');

            Route::post('adminLogin',[AdmController::class,'index'])
                ->name('admin.login')
                ->middleware(isAdminMiddleware::class);
        });
    }
}
