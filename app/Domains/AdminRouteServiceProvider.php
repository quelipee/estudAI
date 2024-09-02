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

            Route::get('/',function(){
                $courses = Course::all();
                return view('index',compact('courses')); //TODO lembrar depois
            })->name('index');

            Route::get('edit/{course}',function (Course $course){
                $categories = Category::cases();
                return view('edit',compact('course','categories'));
            })->name('edit');
            Route::put('updateCourse/{course}',[CourseController::class,'updateCourse'])
                ->name('courses.update');


            Route::post('newCourses', [CourseController::class,'newCourses'])->name('courses.create');
            Route::get('courses',[CourseController::class,'courses'])->name('courses');
            Route::post('deleteCourse/{course}',[CourseController::class,'deleteCourse'])->name('courses.destroy');
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
