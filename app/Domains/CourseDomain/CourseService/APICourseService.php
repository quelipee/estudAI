<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\CourseDomain\Interfaces\APICourseContracts;
use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class APICourseService implements APICourseContracts
{
    public function findCourseById(int $id): Course
    {
        return Course::query()->where('id',$id)->first();
    }

    public function findTopicById(int $id): Topic
    {
        return Topic::query()->where('id',$id)->first();
    }

    public function getYourTopicsByUser(int $id): Collection
    {
        return Topic::query()->where('course_id',$id)->get();
    }

    public function getYourCoursesByUser(): Collection
    {
        $user = Auth::user();
        return $user->courses;
    }

    public function getUnregisteredCoursesForUser(): Collection
    {
        return Course::query()->WithCount('users')->whereDoesntHave('users',function ($query) {
            $query->where('user_id',Auth::id());
        })->get();
    }
}
