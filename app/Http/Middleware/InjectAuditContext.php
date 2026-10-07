<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InjectAuditContext
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Generate request ID if not present
        $requestId = $request->header('X-Request-ID') ?? 'req_' . Str::ulid();
        $request->headers->set('X-Request-ID', $requestId);

        // Store in session for audit logging
        session(['request_id' => $requestId]);
        session(['request_timestamp' => now()]);

        // Measure request duration
        $startTime = microtime(true);

        $response = $next($request);

        // Calculate duration
        $duration = round((microtime(true) - $startTime) * 1000);
        $request->attributes->set('duration_ms', $duration);

        // Add request ID to response headers
        $response->header('X-Request-ID', $requestId);

        return $response;
    }
}
