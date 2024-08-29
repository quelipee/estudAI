<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\Exceptions\CourseException;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Models\Course;
use App\Models\Topic;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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

        if ($dto->topics != null and $dto->topics != ['']) {
            foreach ($dto->topics as $topic) {
                Topic::create([
                    'title' => $topic['title'],
                    'topic' => $topic['topic'],
                    'course_id' => $course->id
                ]);
            }
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

    /**
     * @throws CourseException
     */
    public function updateCourse(newCourseDTO $dto, int $id): Course
    {
        if (Course::query()->where(['id' => $id])->doesntExist()){
            Log::error('Not found!!');
            throw CourseException::courseNotfound($dto->title);
        }
        Course::query()->where('id', $id)->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'category' => $dto->category,
            'updated_at' => now()
        ]);

        if ($dto->topics != null and $dto->topics != ['']){
            Topic::query()->where('course_id',$id)->update([
                'title' => $dto->topics['title'],
                'topic' => $dto->topics['topic'],
                'updated_at' => now(),
            ]);
        }

        Log::info('Course update with success!!');
        return Course::find($id);
    }

    public function existCourse($title) : bool
    {
        return Course::where('title',$title)->exists();
    }
}
