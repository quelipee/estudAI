<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Models\Course;
use Exception;
use Illuminate\Support\Collection;

class CourseService
{
    public function __construct(
        protected Course $course
    ){}


    /**
     * @throws Exception
     */
    public function addCourse(newCourseDTO $dto) : Course
    {
        if ($this->existCourse($dto->title) === true ){
            throw new Exception('Course already exist');
        }

        return new $this->course([
            'title' => $dto->title,
            'description' => $dto->description,
            'category' => $dto->category,
        ]);

    }

    public function existCourse($title) : bool
    {
        return $this->course::where('title',$title)->exists();
    }

    public function getAllCourses(): Collection
    {
        $courses = Course::all()->map(function (Course $course){
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'category' => $course->category,
                'created_at' => $course->created_at,
                'created_up' => $course->created_up,
            ];
        });

        return collect($courses);
    }
}
