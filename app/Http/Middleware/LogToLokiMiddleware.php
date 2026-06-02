<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogToLokiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $response = $next($request);
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $statusCode = $response->getStatusCode();
        
        // Tentukan level berdasarkan status code
        $level = match(true) {
            $statusCode >= 500 => 'error',
            $statusCode >= 400 => 'warn',
            default            => 'info',
        };

        $context = [
            'http_method'      => $request->method(),
            'http_uri'         => $request->getRequestUri(),
            'http_status_code' => $statusCode,
            'duration_ms'      => $duration,
            'ip_address'       => $request->ip(),
            'environment'      => app()->environment(),
            'service_name'     => 'laravel-app',
            'user_agent'       => $request->userAgent(),
        ];

        // Key flat (tidak nested) agar Loki mudah parse sebagai label
        match($level) {
            'error' => Log::error("HTTP {$request->method()} {$request->getRequestUri()} {$statusCode}", $context),
            'warn'  => Log::warning("HTTP {$request->method()} {$request->getRequestUri()} {$statusCode}", $context),
            default => Log::info("HTTP {$request->method()} {$request->getRequestUri()} {$statusCode}", $context),
        };

        return $response;
    }
}