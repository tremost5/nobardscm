<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleRegistrationSubmission
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'registration-submit:'.$request->ip();

        $executed = RateLimiter::attempt(
            $key,
            3,
            function () use ($next, $request) {
                return $next($request);
            },
            60,
        );

        if (! $executed) {
            abort(429, 'Too many registration attempts. Please try again later.');
        }

        return $executed;
    }
}
