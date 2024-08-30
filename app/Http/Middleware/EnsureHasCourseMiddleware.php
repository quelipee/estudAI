<?php

namespace App\Http\Middleware;

use App\Domains\CourseDomain\Exceptions\CourseException;
use App\Domains\CourseDomain\Exceptions\TopicException;
use App\Domains\UserDomain\UserException\UserException;
use App\Models\Course;
use App\Models\Topic;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasCourseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $topic = Topic::where('id',request('topic'))->first();
        if (!$topic)
        {
            return throw TopicException::topicNotFound($topic);
        }

        $courseId = request('course');
        $course = Course::where('id',$courseId->id)->first();
        if (!$course)
        {
            return throw CourseException::courseNotFound($course->title);
        }
        $user = Auth::user();

        if (!$user->courses->contains($course)) {
            throw UserException::notUnregisteredCourse();
        }

        if (!$course->topics->contains($topic)) {
            throw TopicException::topicNotFound($topic->title);
        }

        return $next($request);
    }
}
