<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HousekeeperMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Block soft-deleted users
        if (!auth()->check() || auth()->user()->trashed()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'This account has been deactivated.']);
        }

        if (!auth()->user()->isHousekeeper()) {
            abort(403, 'Access denied. Housekeepers only.');
        }

        // if (!auth()->check() || !auth()->user()->isHousekeeper()) {
        //     abort(403, 'Access denied. Housekeepers only.');
        // }

        return $next($request);
    }
}