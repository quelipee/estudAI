<?php

namespace App\Domains\CourseDomain\Interfaces;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseDTO\TopicDTO;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Support\Collection;

interface CourseTopicsContracts
{
    public function addCourse(newCourseDTO $dto) : Course;
    public function getAllCourses() : Collection;
    public function destroyCourse(Course $course) : bool;
    public function updateCourse(newCourseDTO $dto, int $id) : Course;

    public function addTopic(TopicDTO $dto) : Topic;

    public function destroyTopic(Topic $topic) : bool;

    public function updateTopic(TopicDTO $dto,Topic $topic) : Topic;
}
