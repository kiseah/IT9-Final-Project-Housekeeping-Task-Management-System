<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        // ✅ Block soft-deleted users from any access
        if (!auth()->check() || auth()->user()->trashed()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'This account has been deactivated.']);
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Access denied. Admins only.');
        }

        // if (!auth()->check() || !auth()->user()->isAdmin()) {
        //     abort(403, 'Access denied. Admins only.');
        // }

        return $next($request);
    }
}