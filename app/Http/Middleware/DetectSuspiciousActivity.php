<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class DetectSuspiciousActivity
{
    /**
     * Suspicious patterns to detect
     */
    protected array $suspiciousPatterns = [
        '/\.\./',  // Directory traversal
        '/(union|select|insert|update|delete|drop|create|alter|exec|execute|script|javascript|eval|expression)/i',  // SQL injection
        '/<script|<iframe|<object|<embed|onerror|onload|onclick/i',  // XSS attempts
        '/(\%27)|(\')|(\%23)|(#)/i',  // SQL injection characters (removed -- to avoid issues)
        '/(<|%3C).*script.*(>|%3E)/i',  // Script tags
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        // Check for suspicious patterns in URL
        if ($this->hasSuspiciousInput($request->fullUrl())) {
            $this->logSuspiciousActivity($request, 'Suspicious URL pattern detected');
            return $this->blockRequest('Invalid request');
        }

        // Check for suspicious patterns in input
        foreach ($request->all() as $key => $value) {
            if (is_string($value) && $this->hasSuspiciousInput($value)) {
                $this->logSuspiciousActivity($request, "Suspicious input in field: {$key}");
                return $this->blockRequest('Invalid input detected');
            }
        }

        // Check for bot/crawler patterns (optional - adjust based on needs)
        if ($this->isSuspiciousUserAgent($userAgent)) {
            $this->logSuspiciousActivity($request, 'Suspicious user agent detected');
        }

        // Rate limit suspicious IPs more aggressively
        if ($this->isSuspiciousIP($ip)) {
            $key = 'suspicious:' . $ip;
            if (RateLimiter::tooManyAttempts($key, 10)) {
                $this->logSuspiciousActivity($request, 'Too many requests from suspicious IP');
                return $this->blockRequest('Too many requests');
            }
            RateLimiter::hit($key, 60);
        }

        return $next($request);
    }

    /**
     * Check if input contains suspicious patterns
     */
    protected function hasSuspiciousInput(string $input): bool
    {
        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check for suspicious user agents
     */
    protected function isSuspiciousUserAgent(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $suspiciousAgents = [
            'sqlmap',
            'nikto',
            'nmap',
            'masscan',
            'metasploit',
            'havij',
            'acunetix',
        ];

        $userAgentLower = strtolower($userAgent);
        foreach ($suspiciousAgents as $agent) {
            if (str_contains($userAgentLower, $agent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP has been flagged as suspicious
     */
    protected function isSuspiciousIP(string $ip): bool
    {
        // You can expand this to check against a database or external service
        // For now, just check if IP has multiple failed logins
        return RateLimiter::attempts('login:' . $ip) > 3;
    }

    /**
     * Log suspicious activity
     */
    protected function logSuspiciousActivity(Request $request, string $reason): void
    {
        Log::warning('Suspicious activity detected', [
            'reason' => $reason,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'input' => $request->except(['password', 'password_confirmation']),
            'user_id' => auth()->id(),
        ]);

        // Optionally log to database for audit trail
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'SECURITY_ALERT',
            'description' => $reason,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'details' => json_encode([
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]),
        ]);
    }

    /**
     * Block the request
     */
    protected function blockRequest(string $message): Response
    {
        return response()->json([
            'message' => $message,
        ], 403);
    }
}
