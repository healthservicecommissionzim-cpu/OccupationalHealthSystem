<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only cache GET requests for specific dashboard routes
        if ($request->isMethod('get') && in_array($request->route()->getName(), [
            'dashboards.pmd',
            'dashboards.pmd_pending_cases',
        ])) {
            // Set cache headers for 15 minutes
            $response = $next($request);
            
            // Don't cache if refresh parameter is present
            if (!$request->has('refresh')) {
                $response->header('Cache-Control', 'public, max-age=900'); // 15 minutes
                $response->header('Pragma', 'public');
                $response->header('Expires', gmdate('D, d M Y H:i:s', time() + 900) . ' GMT');
            } else {
                // Force no cache if refresh requested
                $response->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                $response->header('Pragma', 'no-cache');
                $response->header('Expires', '0');
            }
            
            return $response;
        }

        return $next($request);
    }
}
