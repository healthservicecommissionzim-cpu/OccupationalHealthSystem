<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoSDashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * Verifies user has CoS role and view_all_cases permission before accessing dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has CoS role
        if (!$user->hasRole('hsc_conditions')) {
            abort(403, 'Unauthorized: You do not have access to the CoS Dashboard.');
        }

        // Check if user has view_all_cases permission
        if (!$user->hasPermissionTo('view_all_cases')) {
            abort(403, 'Unauthorized: You lack required permissions for the CoS Dashboard.');
        }

        // Log access attempt
        \Illuminate\Support\Facades\Log::info('CoS Dashboard accessed', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'route' => $request->path(),
            'timestamp' => now(),
        ]);

        return $next($request);
    }
}
