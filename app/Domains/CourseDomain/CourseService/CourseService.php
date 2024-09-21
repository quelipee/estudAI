<?php

namespace App\Domains\CourseDomain\CourseService;

use App\Domains\ChatDomain\ChatContracts;
use App\Domains\CourseDomain\CourseDTO\newCourseDTO;
use App\Domains\CourseDomain\CourseDTO\TopicDTO;
use App\Domains\CourseDomain\Exceptions\CourseException;
use App\Domains\CourseDomain\Exceptions\TopicException;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Events\CourseEvent;
use App\Events\TopicEvent;
use App\Events\YourCourseEvent;
use App\Models\Course;
use App\Models\Topic;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class CourseService implements CourseTopicsContracts
{
    public function __construct(protected ChatContracts $chatContracts){}

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

        $this->getDispatch();
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
        $this->getDispatch();
        $this->getDispatchYourCourse();
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
        $this->getDispatch();
        $this->getDispatchYourCourse();
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

        $this->chatContracts->receive_topic($course,$topic->id);

        Event::dispatch(new TopicEvent(Topic::query()
            ->where('course_id',$dto->course_id)
            ->get()->toArray()));
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
        $courseId = $topic->course_id;
        $topic->delete();
        Log::info('Topic delete with success!!');
        Event::dispatch(new TopicEvent(
            Topic::query()->where('course_id',$courseId)
                ->get()->toArray()
        ));
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
        $course = Course::query()->where('id',$dto->course_id)->first();
        $this->chatContracts->updated_topic_message(
            $course,$topic->id,$dto->roleModelId,
            $dto->roleUserId, $dto->title, $dto->topic);

        Event::dispatch(new TopicEvent(
            Topic::query()->where('course_id',$dto->course_id)
                ->get()->toArray()));
        return $topic;
    }

    /**
     * @return void
     */
    public function getDispatch(): void
    {
        $userId = User::find(Auth::id())->first();
        Event::dispatch(new CourseEvent(
            Course::query()->WithCount('users')->whereDoesntHave('users', function ($query) use ($userId) {
                $query->where('user_id', $userId->id);
            })->get()->toArray()
        ));
    }

    /**
     * @return void
     */
    public function getDispatchYourCourse(): void
    {
        $user = User::find(Auth::id())->first();
        Event::dispatch(new YourCourseEvent($user->courses->toArray()));
    }
}
