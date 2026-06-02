<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use OpenTelemetry\API\Globals;

class OtelMetricsMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {

        // error_log('=== MIDDLEWARE CALLED: ' . $request->getRequestUri());

        $startTime = hrtime(true);
        $response = $next($request);
        $duration = (hrtime(true) - $startTime) / 1e9;

        // error_log('=== MeterProvider class: ' . get_class(\OpenTelemetry\API\Globals::meterProvider()));

        try {
            $meter = Globals::meterProvider()->getMeter('laravel-app');
                  
            // \Illuminate\Support\Facades\Log::info('MeterProvider class: ' . get_class(Globals::meterProvider()));

            $histogram = $meter->createHistogram(
                'http.server.request.duration',
                's',
                'Duration of HTTP server requests'
            );
            $histogram->record($duration, [
                'http.request.method'        => $request->method(),
                'http.route'                 => $request->route()?->uri() ?? $request->path(),
                'http.response.status_code'  => $response->getStatusCode(),
                'url.scheme'                 => $request->getScheme(),
                'server.address'             => $request->getHost(),
            ]);

            // Force flush to ensure metrics are sent before the response is returned
            $provider = Globals::meterProvider();
            if (method_exists($provider, 'forceFlush')) {
                $provider->forceFlush();
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('OTel metrics error: ' . $e->getMessage());
        }

        return $response;
    }
}