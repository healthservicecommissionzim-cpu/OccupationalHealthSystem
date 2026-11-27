<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyDocumentationAccess
{
    /**
     * Handle an incoming request to documentation pages.
     * Requires password re-verification for security.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if documentation access has been verified in this session
        if (!session('documentation_verified')) {
            // If AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password verification required',
                    'requires_verification' => true,
                ], 403);
            }

            // For regular requests, redirect to dashboard with modal trigger
            return redirect()->route('dashboards.super_admin')
                ->with('show_documentation_password_modal', true);
        }

        // Check if verification has expired (30 minutes)
        $verifiedAt = session('documentation_verified_at');
        if ($verifiedAt && now()->diffInMinutes($verifiedAt) > 30) {
            // Clear expired verification
            session()->forget(['documentation_verified', 'documentation_verified_at']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your verification has expired. Please verify again.',
                    'requires_verification' => true,
                ], 403);
            }

            return redirect()->route('dashboards.super_admin')
                ->with('show_documentation_password_modal', true)
                ->with('error', 'Your verification has expired. Please enter your password again.');
        }

        return $next($request);
    }
}
