<?php

namespace App\Http\Middleware;

use App\Domains\UserDomain\UserException\UserException;
use App\Models\Course;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicateEnrollment
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $course = $request->route('course');
        $exist = $user->courses()->where('course_id', $course->id)->exists();
        if ($exist) {
            throw UserException::userAlreadyEnrolled();
        }
        return $next($request);
    }
}
