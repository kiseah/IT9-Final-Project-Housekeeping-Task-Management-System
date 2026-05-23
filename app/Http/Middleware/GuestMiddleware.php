<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Block soft-deleted users
        if (!auth()->check() || auth()->user()->trashed()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'This account has been deactivated.']);
        }

        if (!auth()->user()->isGuest()) {
            abort(403, 'Access denied. Guests only.');
        }

        // if (!auth()->check() || !auth()->user()->isGuest()) {
        //     abort(403, 'Access denied. Guests only.');
        // }

        return $next($request);
    }
}