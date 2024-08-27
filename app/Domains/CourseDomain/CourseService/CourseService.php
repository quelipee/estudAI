<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\Exceptions\CourseException;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Models\Course;
use App\Models\Topic;
use Exception;
use Illuminate\Support\Collection;

class CourseService implements CourseTopicsContracts
{
    /**
     * @throws Exception
     */
    public function addCourse(newCourseDTO $dto) : Course
    {
        if ($this->existCourse($dto->title) === true ){
            throw CourseException::courseExists($dto->title);
        }

        $course = Course::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'category' => $dto->category,
        ]);

        foreach ($dto->topics as $key => $topic){
            $title = $key;
            Topic::create([
                'title' => $title,
                'topics' => $topic,
                'course_id' => $course->id
            ]);
        }

        return $course;
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

    /**
     * @throws CourseException
     */
    public function destroyCourse(newCourseDTO $dto) : bool
    {
        if (!$this->existCourse($dto->title)){
            throw CourseException::courseNotfound($dto->title);
        }
        $course = Course::query()->where('title', $dto->title)->delete();

        if (!$course){
            throw CourseException::courseNotDeleted($dto->title);
        }

        return true;
    }

    public function existCourse($title) : bool
    {
        return Course::where('title',$title)->exists();
    }
}
