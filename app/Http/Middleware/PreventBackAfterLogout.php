<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackAfterLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Don't set cache headers on streamed responses or file responses
        if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse ||
            $response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            return $response;
        }

        // Check if response has the header method (some response types don't)
        if (!method_exists($response, 'header')) {
            return $response;
        }

        // Don't override cache headers if they're already set for inline viewing
        if ($response->headers->has('Content-Disposition') && strpos($response->headers->get('Content-Disposition'), 'inline') !== false) {
            return $response;
        }

        // Set cache headers to prevent browser caching of authenticated pages
        return $response
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
