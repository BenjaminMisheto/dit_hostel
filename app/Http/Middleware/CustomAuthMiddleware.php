<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated with the 'web' guard (default guard)
        if (!Auth::guard('web')->check()) {
            return redirect()->route('user.load')
                ->withErrors(['error' => 'Session expired. Please login again!']);
        }

        return $next($request);
    }
}
