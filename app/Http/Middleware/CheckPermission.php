<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission  The permission slug to check
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Get authenticated user
        $user = auth()->user();

        // Redirect unauthenticated users to login
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to access this resource.');
        }

        // Allow Super Admin to bypass permission checks
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if user has the required permission
        if ($user->hasPermission($permission)) {
            return $next($request);
        }

        // User doesn't have permission - return 403 error
        abort(403, "You do not have permission to access '{$permission}'.");
    }
}
