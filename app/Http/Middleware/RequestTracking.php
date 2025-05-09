<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

// Adds request tracing and performance monitoring to all requests
class RequestTracking
{
    // Handle request by adding ID and performance tracking
    public function handle(Request $request, Closure $next)
    {
        // Generate a unique request ID if not already present
        if (!$request->hasHeader('X-Request-ID')) {
            $requestId = Str::uuid()->toString();
            $request->headers->set('X-Request-ID', $requestId);
        } else {
            $requestId = $request->header('X-Request-ID');
        }
        
        // Set request ID in logger context for all log entries
        Log::withContext(['request_id' => $requestId]);
        
        // Log information about the start of the request
        Log::debug('Request started', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        $startTime = microtime(true);
        
        // Continue through the middleware stack and handle the request
        $response = $next($request);
        
        // Measure processing time
        $duration = microtime(true) - $startTime;
        
        // Add X-Request-ID header to response for client-side tracking
        $response->headers->set('X-Request-ID', $requestId);
        
        // Log information about the completion of the request
        Log::debug('Request completed', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
            'duration_ms' => round($duration * 1000, 2)
        ]);
        
        return $response;
    }
} 