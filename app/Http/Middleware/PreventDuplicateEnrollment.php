<?php

namespace App\Http\Middleware;

use App\Domains\UserDomain\UserException\UserException;
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
        $course = $request->route("course");

        if ($user->courses()->exists($course)) {
            throw UserException::userAlreadyEnrolled();
        }
        return $next($request);
    }
}
