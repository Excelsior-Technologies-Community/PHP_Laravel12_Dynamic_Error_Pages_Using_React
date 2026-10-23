<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIp
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to error dashboard & unblock routes so admin/tester is never locked out
        if ($request->is('error-dashboard*') || $request->is('error-unblock*') || $request->is('error-blacklist*')) {
            return $next($request);
        }

        $clientIp = $request->ip();

        // Check if IP is in the blacklist
        $blocked = BlockedIp::where('ip_address', $clientIp)->first();

        if ($blocked) {
            abort(403, 'Access Denied: Your IP address (' . $clientIp . ') has been blacklisted by Security Radar. Reason: ' . ($blocked->reason ?: 'Malicious activity detected.'));
        }

        return $next($request);
    }
}
