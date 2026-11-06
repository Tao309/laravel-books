<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MatchCurrentUser
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = (int) $request->offsetGet('user_id');

        if ($userId !== Auth::user()->id) {
            throw new AccessDeniedHttpException('You are not authorized to access this Resource');
        }

        return $next($request);
    }
}
