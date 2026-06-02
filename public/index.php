<?php

// putenv('OTEL_PHP_AUTOLOAD_ENABLED=true');
// putenv('OTEL_SERVICE_NAME=laravel-app');

// // TRACES
// putenv('OTEL_TRACES_EXPORTER=otlp');
// putenv('OTEL_EXPORTER_OTLP_TRACES_PROTOCOL=http/protobuf');
// putenv('OTEL_EXPORTER_OTLP_TRACES_ENDPOINT=http://127.0.0.1:4318/v1/traces');

// putenv('OTEL_EXPORTER_OTLP_ENDPOINT=http://127.0.0.1:4318');
// putenv('OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf');
// putenv('OTEL_PROPAGATORS=tracecontext,baggage');
// putenv('OTEL_LOGS_EXPORTER=none');

// // METRICS
// putenv('OTEL_PHP_DETACHED_PROCESS=false');
// putenv('OTEL_METRICS_EXPORTER=otlp');
// putenv('OTEL_EXPORTER_OTLP_METRICS_PROTOCOL=http/protobuf');
// putenv('OTEL_EXPORTER_OTLP_METRICS_ENDPOINT=http://127.0.0.1:4318/v1/metrics');

// Trik Pengaman untuk FrankenPHP Worker Mode / Laravel Octane
if (!isset($_SERVER['REMOTE_ADDR'])) {
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

// while (frankenphp_handle_request(function () use ($app) {
	// $kernel = $app->make(Kernel::class);

	// $response = $kernel->handle(
    // 	$request = Request::capture()
	// )->send();

	// $kernel->terminate($request, $response);

// }));

//while (frankenphp_handle_request(function () use ($app) {
//    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
//   $request = Request::capture();
//    $response = $kernel->handle($request)->send();
//    $kernel->terminate($request, $response);
//}));


// while (frankenphp_handle_request(function () use ($app) {
//     // 1. Tangkap request yang masuk dari FrankenPHP
//     $request = Request::capture();

//     // 2. Jalankan kernel Laravel
//     $kernel = $app->make(Kernel::class);
//     $response = $kernel->handle($request);

//     // 3. Kirim response ke output buffer agar ditangkap FrankenPHP
//     $response->send();

//     // 4. Selesaikan siklus request
//     $kernel->terminate($request, $response);

//     // 5. Bersihkan instance kernel untuk request berikutnya (PENTING di Worker Mode agar tidak memory leak)
//     $app->terminate(); 
// }));

// while (frankenphp_handle_request(function () use ($app) {
//     // TAMBAH INI — paling atas di dalam loop
//     error_log('=== WORKER LOOP DIPANGGIL: ' . date('Y-m-d H:i:s') . ' ===');
    
//     $request = Request::capture();
    
//     // TAMBAH INI JUGA
//     error_log('=== REQUEST URI: ' . $request->getRequestUri() . ' ===');
    
//     $kernel = $app->make(Kernel::class);
//     $response = $kernel->handle($request);
//     $response->send();
//     $kernel->terminate($request, $response);
// }));

while (frankenphp_handle_request(function () use ($app) {
    $request = Request::capture();
    $kernel = $app->make(Kernel::class);
    $response = $kernel->handle($request);
    
    // error_log('=== SETELAH HANDLE, status: ' . $response->getStatusCode());
    // error_log('=== KERNEL CLASS: ' . get_class($kernel));
    
    $response->send();
    $kernel->terminate($request, $response);
}));