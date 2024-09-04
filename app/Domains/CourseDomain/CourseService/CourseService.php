<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseDTO\TopicDTO;
use App\Domains\CourseDomain\Exceptions\CourseException;
use App\Domains\CourseDomain\Exceptions\TopicException;
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
            'status' => $dto->status,
        ]);

        foreach ($dto->topics as $topic) {
            if ($topic['title'] != null or  $topic['topic'] != null) {
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
    public function destroyCourse(Course $course) : bool
    {
        if (!$this->existCourse($course->title)){
            throw CourseException::courseNotfound($course->title);
        }

        $course->delete();
        Log::info('Course delete with success!!');
        if (!$course){
            throw CourseException::courseNotDeleted($course->title);
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
            'status' => $dto->status,
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

    /**
     * @throws TopicException
     */
    public function addTopic(TopicDTO $dto): Topic
    {
        $course = Course::query()
            ->where('id', $dto->course_id)
            ->first();
        if (!$dto->course_id)
        {
            throw TopicException::topicNotFound($course->title);
        }

        $topic = new Topic([
            'title' => $dto->title,
            'topic' => $dto->topic,
        ]);

        $topic->course()->associate($course);
        $topic->save();

        return $topic;
    }

    public function existCourse($title) : bool
    {
        return Course::where('title',$title)->exists();
    }

    /**
     * @throws TopicException
     */
    public function destroyTopic(Topic $topic): bool
    {
        if (empty($topic->course_id))
        {
            throw TopicException::topicNotFound($topic->title);
        }

        $topic->delete();
        Log::info('Topic delete with success!!');
        return true;
    }

    public function updateTopic(TopicDTO $dto,Topic $topic): Topic
    {
        $topic->fill([
            'topic' => $dto->topic,
            'title' => $dto->title,
            'course_id' => $dto->course_id,
            'updated_at' => now(),
        ])->save();
        return $topic;
    }
}
