<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next)
{
    // Allow unauthenticated access to the logout route
    if ($request->routeIs('admin.logout')) {
        return $next($request);
    }

    // Check if the user is authenticated with the 'admin' guard
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return $next($request);
}

}
