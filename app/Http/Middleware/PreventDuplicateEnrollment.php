<?php

namespace App\Http\Middleware;

use App\Models\Course;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $course = Course::find($request->segment(3));
        if ($user->load('courses')->courses->contains($course)) {
            throw new Exception('User already enrolled in this course.');
        }
        return $next($request);
    }
}
