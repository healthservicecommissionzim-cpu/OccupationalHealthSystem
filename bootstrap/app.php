<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\InjectAuditContext::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'prevent_back' => \App\Http\Middleware\PreventBackAfterLogout::class,
            'approval_status' => \App\Http\Middleware\CheckApprovalStatus::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'witness.management.access' => \App\Http\Middleware\CheckWitnessManagementAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle CSRF token mismatch (419 Page Expired) and session timeout
        $exceptions->render(function (Throwable $e, $request) {
            // Handle TokenMismatchException (419 Page Expired)
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                // If it's an AJAX request, return JSON response
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Session expired. Please refresh and try again.'
                    ], 419);
                }
                
                // For regular requests, redirect to login
                return redirect()
                    ->route('login')
                    ->with('error', 'Your session has expired. Please log in again.');
            }
        });
    })->create();
