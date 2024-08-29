<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Allow unauthenticated access to the logout route
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        // Check if the user is authenticated with the 'web' guard
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard'); // or any other route you want to redirect to
        }

        return $next($request);
    }
}
