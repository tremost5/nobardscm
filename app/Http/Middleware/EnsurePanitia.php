<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePanitia
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isPanitia()) {
            return redirect('/dashboard')->with('error', 'Anda hanya dapat mengakses halaman kehadiran.');
        }

        $user = auth()->user();
        if ($user->force_password_change && ! $request->routeIs('panitia.password.change') && ! $request->routeIs('panitia.password.change.update')) {
            return redirect()->route('panitia.password.change');
        }

        return $next($request);
    }
}
