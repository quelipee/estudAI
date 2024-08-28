<?php

namespace App\Http\Middleware;

use App\Domains\UserDomain\UserException\UserException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws UserException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user){
            throw UserException::notLoggedIn();
        }

        if (!$user->is_admin){
            throw UserException::notAuthorized();
        }

        config(['usertype.provider_default' => 'admin']);
        return $next($request);
    }
}
