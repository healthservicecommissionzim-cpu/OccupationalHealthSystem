<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DashboardCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only cache GET requests for dashboards
        if ($request->isMethod('get') && $request->path() === 'dashboards/super_admin') {
            $response->header('Cache-Control', 'public, max-age=60');
            $response->header('Pragma', 'cache');
        }

        return $response;
    }
}
