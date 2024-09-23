<?php

namespace App\Domains\CourseDomain\Interfaces;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;

interface APICourseContracts
{
    public function findCourseById(int $id) : Course;
    public function findTopicById(int $id) : Topic;
    public function getYourTopicsByUser(int $id) : Collection;
    public function getYourCoursesByUser() : Collection;
    public function getUnregisteredCoursesForUser() : Collection;
}
