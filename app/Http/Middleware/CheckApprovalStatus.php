<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApprovalStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get authenticated user
        $user = auth()->user();

        // Allow unauthenticated requests to pass through
        if (!$user) {
            return $next($request);
        }

        // Allow Super Admin to bypass approval check
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Check if user is approved
        if ($user->isApproved()) {
            return $next($request);
        }

        // Handle JSON/AJAX requests differently to avoid redirect loops
        if ($request->expectsJson() || $request->wantsJson()) {
            if ($user->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is pending approval. A Super Admin will review your application shortly.'
                ], 403);
            }

            if ($user->isRejected()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your application was rejected. Reason: ' . ($user->rejection_reason ?? 'No reason provided')
                ], 403);
            }

            return response()->json([
                'success' => false,
                'message' => 'Your account status is not recognized. Please contact support.'
            ], 403);
        }

        // Redirect pending users to pending screen
        if ($user->isPending()) {
            return redirect()->route('pending-user')
                ->with('warning', 'Your account is pending approval. A Super Admin will review your application shortly.');
        }

        // Redirect rejected users to pending screen with rejection reason
        if ($user->isRejected()) {
            return redirect()->route('pending-user')
                ->with('error', 'Your application was rejected. Reason: ' . ($user->rejection_reason ?? 'No reason provided'));
        }

        // Default: redirect to pending screen
        return redirect()->route('pending-user')
            ->with('warning', 'Your account status is not recognized. Please contact support.');
    }
}
