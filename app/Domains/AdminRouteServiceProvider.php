<?php

namespace App\Domains;

use App\Domains\CourseDomain\CourseController\CourseController;
use App\Http\Middleware\isAdminMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminRouteServiceProvider extends RouteServiceProvider
{
    public function map() : void
    {
        Route::prefix('admin')->middleware(['auth:sanctum', isAdminMiddleware::class])->group(function () {
            Route::post('newCourses', [CourseController::class,'newCourses'])->name('newCourses');
            Route::get('courses',[CourseController::class,'courses'])->name('courses');
            Route::post('deleteCourse/{course}',[CourseController::class,'deleteCourse'])->name('deleteCourse');
            Route::put('updateCourse/{course}',[CourseController::class,'updateCourse'])->name('updateCourse');
        });
    }
}
