<?php

namespace App\Http\Middleware;

use App\Models\Course;
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
        $course = Course::find($request->segment(3));
        $user = Auth::user();

        if (!$user->load('courses')->courses->contains($course)) {
            throw new Exception('User is not enrolled in this course');
        }
        return $next($request);
    }
}
