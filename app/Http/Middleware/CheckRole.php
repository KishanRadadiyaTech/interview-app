<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return $request->expectsJson() 
                ? response()->json(['error' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has any of the required roles
        if ($user->role && in_array($user->role->slug, $roles)) {
            return $next($request);
        }

        // Handle unauthorized access
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized.'], 403);
        }

        return redirect()
            ->route('dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}
