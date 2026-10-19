<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ForensicAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Handle login and redirect to appropriate dashboard.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $request->session()->regenerate();

            // Check if user is approved
            if ($user->isPending()) {
                // Log pending user login attempt
                Log::warning('Pending user login attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'status' => 'pending',
                    'ip_address' => $request->ip(),
                    'timestamp' => now(),
                ]);

                // Log to audit
                ForensicAuditService::log([
                    'action' => 'LOGIN_PENDING_USER',
                    'resource_type' => 'User',
                    'resource_path' => '/login',
                    'auditable_type' => 'App\\Models\\User',
                    'auditable_id' => $user->id,
                    'affected_entity' => "{$user->name} ({$user->email})",
                    'component' => 'LoginController@store',
                    'http_code' => 200,
                    'status' => 'SUCCESS',
                    'classification' => 'SECURITY_SENSITIVE',
                ]);

                // Logout and redirect to pending screen
                Auth::logout();
                return redirect()->route('pending-user')
                    ->with('warning', 'Your account is pending approval. A Super Admin will review your application shortly.');
            }

            if ($user->isRejected()) {
                // Log rejected user login attempt
                Log::warning('Rejected user login attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'status' => 'rejected',
                    'rejection_reason' => $user->rejection_reason,
                    'ip_address' => $request->ip(),
                    'timestamp' => now(),
                ]);

                // Log to audit
                ForensicAuditService::log([
                    'action' => 'LOGIN_REJECTED_USER',
                    'resource_type' => 'User',
                    'resource_path' => '/login',
                    'auditable_type' => 'App\\Models\\User',
                    'auditable_id' => $user->id,
                    'affected_entity' => "{$user->name} ({$user->email})",
                    'component' => 'LoginController@store',
                    'http_code' => 200,
                    'status' => 'SUCCESS',
                    'classification' => 'SECURITY_SENSITIVE',
                ]);

                // Logout and redirect to pending screen with rejection reason
                Auth::logout();
                return redirect()->route('pending-user')
                    ->with('error', 'Your application was rejected. Reason: ' . ($user->rejection_reason ?? 'No reason provided'));
            }

            // User is approved - log successful login
            Log::info('User login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'status' => 'approved',
                'ip_address' => $request->ip(),
                'timestamp' => now(),
            ]);

            // Log to audit
            ForensicAuditService::log([
                'action' => 'LOGIN_SUCCESS',
                'resource_type' => 'User',
                'resource_path' => '/login',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'LoginController@store',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'IMPORTANT',
            ]);

            // Set session flag to show welcome modal (flash - auto-expires after one request)
            $request->session()->flash('just_logged_in', true);

            return redirect()->route($user->getDashboardRoute());
        }

        // Log failed login attempt
        Log::warning('Failed login attempt', [
            'email' => $credentials['email'] ?? null,
            'ip_address' => $request->ip(),
            'timestamp' => now(),
        ]);

        // Log to audit with credentials and suspicious activity detection
        $ipAddress = $request->ip() ?? '0.0.0.0';
        $isSuspiciousIP = $this->isIpSuspicious($ipAddress);
        $passwordHash = hash('sha256', $credentials['password'] ?? '');
        
        ForensicAuditService::log([
            'action' => 'LOGIN_FAILED',
            'resource_type' => 'User',
            'resource_path' => '/login',
            'auditable_type' => 'App\\Models\\User',
            'auditable_id' => null,
            'affected_entity' => $credentials['email'] ?? 'Unknown',
            'component' => 'LoginController@store',
            'http_code' => 401,
            'status' => 'FAILED',
            'error_message' => 'Invalid credentials',
            'classification' => 'SECURITY_SENSITIVE',
            'data_before' => [
                'attempted_email' => $credentials['email'] ?? null,
                'ip_address' => $ipAddress,
                'ip_suspicious' => $isSuspiciousIP,
                'timestamp' => now()->toIso8601String(),
            ],
            'data_after' => [
                'login_attempt_blocked' => true,
                'reason' => 'Invalid credentials',
                'password_hash' => $passwordHash,
                'suspicious_activity_detected' => $isSuspiciousIP,
            ],
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }


    /**
     * Check if an IP address is suspicious based on patterns.
     */
    private function isIpSuspicious(string $ipAddress): bool
    {
        // List of known suspicious/VPN/proxy IPs (can be expanded)
        $suspiciousIps = [
            '192.168.100.50', // Example suspicious IP
            '10.0.0.1',       // Example VPN range
        ];
        
        // Check if IP is in suspicious list
        if (in_array($ipAddress, $suspiciousIps)) {
            return true;
        }
        
        // Check for private/internal IPs (potential internal threats)
        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) === false) {
            return true;
        }
        
        return false;
    }

    /**
     * Handle logout.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        // Log logout
        if ($user) {
            ForensicAuditService::log([
                'action' => 'LOGOUT',
                'resource_type' => 'User',
                'resource_path' => '/logout',
                'auditable_type' => 'App\\Models\\User',
                'auditable_id' => $user->id,
                'affected_entity' => "{$user->name} ({$user->email})",
                'component' => 'LoginController@destroy',
                'http_code' => 200,
                'status' => 'SUCCESS',
                'classification' => 'ROUTINE',
            ]);
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Prevent back button access by adding cache headers
        return redirect()->route('welcome')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }
}
