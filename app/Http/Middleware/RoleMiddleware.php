<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // If no specific roles are provided, allow access
        if (empty($roles)) {
            return $next($request);
        }
        
        // Check if user has any of the required roles
        foreach ($roles as $role) {
            $method = 'is' . ucfirst($role);
            if (method_exists($user, $method) && $user->$method()) {
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles, redirect to dashboard
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}
