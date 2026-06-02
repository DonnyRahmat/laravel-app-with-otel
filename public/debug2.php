<?php
header('Content-Type: text/plain');
echo "OTEL_PHP_AUTOLOAD_ENABLED: " . getenv('OTEL_PHP_AUTOLOAD_ENABLED') . "\n";
echo "OTEL_SERVICE_NAME: " . getenv('OTEL_SERVICE_NAME') . "\n";
echo "OTEL_EXPORTER_OTLP_ENDPOINT: " . getenv('OTEL_EXPORTER_OTLP_ENDPOINT') . "\n";
echo "\n--- Extension Status ---\n";
echo "Extension Loaded: " . (extension_loaded('opentelemetry') ? 'Yes' : 'No') . "\n";
echo "All: " . shell_exec('printenv') . "\n";
