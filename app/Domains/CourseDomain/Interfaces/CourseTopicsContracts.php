<?php

namespace App\Domains\CourseDomain\Interfaces;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Models\Course;
use Illuminate\Support\Collection;

interface CourseTopicsContracts
{
    public function addCourse(newCourseDTO $dto) : Course;
    public function getAllCourses() : Collection;
    public function destroyCourse(newCourseDTO $dto) : bool;
    public function updateCourse(newCourseDTO $dto, int $id) : Course;
}
