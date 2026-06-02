<?php
// header('Content-Type: text/plain');
// echo "OTel Autoload: " . ini_get('otel.php.autoload_enabled') . "\n";
// echo "OTel Endpoint: " . ini_get('otel.exporter.otlp.endpoint') . "\n";
// echo "OTel Service Name: " . ini_get('otel.service.name') . "\n";
// echo "\n--- Loaded INI Files ---\n";
// echo php_ini_scanned_files();


// header('Content-Type: text/plain');
// echo "OTEL_PHP_AUTOLOAD_ENABLED: " . getenv('OTEL_PHP_AUTOLOAD_ENABLED') . "\n";
// echo "OTEL_SERVICE_NAME: " . getenv('OTEL_SERVICE_NAME') . "\n";
// echo "OTEL_EXPORTER_OTLP_ENDPOINT: " . getenv('OTEL_EXPORTER_OTLP_ENDPOINT') . "\n";
// echo "\n--- Extension Status ---\n";
// echo "Extension Loaded: " . (extension_loaded('opentelemetry') ? 'Yes' : 'No') . "\n";


header('Content-Type: text/plain');

echo "--- OTel Environment Variables ---\n";
echo "OTEL_PHP_AUTOLOAD_ENABLED: " . (getenv('OTEL_PHP_AUTOLOAD_ENABLED') ?: 'false') . "\n";
echo "OTEL_SERVICE_NAME: " . (getenv('OTEL_SERVICE_NAME') ?: 'not set') . "\n";
echo "OTEL_EXPORTER_OTLP_ENDPOINT: " . (getenv('OTEL_EXPORTER_OTLP_ENDPOINT') ?: 'not set') . "\n";
echo "OTEL_EXPORTER_OTLP_PROTOCOL: " . (getenv('OTEL_EXPORTER_OTLP_PROTOCOL') ?: 'not set') . "\n";

echo "\n--- Extension Status ---\n";
echo "Extension 'opentelemetry' Loaded: " . (extension_loaded('opentelemetry') ? 'Yes' : 'No') . "\n";

echo "\n--- Loaded INI Files ---\n";
echo php_ini_scanned_files();