<?php

/**
 * Utility script to clear Laravel caches on shared hosting without SSH.
 * URL: https://api.ochotierras.cl/fix_routes.php
 */

define('LARAVEL_START', microtime(true));

// Load Composer's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap code to get the Application instance (similar to index.php but we don't handle request)
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Get the Console Kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<html><body><h1>Cache Clearing Utility</h1><pre>";

try {
    echo "1. Clearing Route Cache...\n";
    $kernel->call('route:clear');
    echo htmlspecialchars($kernel->output()) . "\n";

    echo "2. Clearing Config Cache...\n";
    $kernel->call('config:clear');
    echo htmlspecialchars($kernel->output()) . "\n";

    echo "3. Clearing Application Cache...\n";
    $kernel->call('cache:clear');
    echo htmlspecialchars($kernel->output()) . "\n";

    echo "4. Re-caching Routes (Optional but recommended for prod)...\n";
    // Using call to ensure it works, though route:cache might fail if there are closure routes.
    // We'll try it.
    try {
        $kernel->call('route:cache');
        echo "Route cache rebuilt successfully.\n";
    } catch (\Throwable $e) {
        echo "Warning: Could not rebuild route cache (Closure routes?): " . $e->getMessage() . "\n";
        echo "Routes are currently cleared (uncached), which is fine.\n";
    }

    echo "\n<strong style='color:green'>SUCCESS! New routes (like /api/packs) should now be visible.</strong>";

} catch (\Throwable $e) {
    echo "<strong style='color:red'>ERROR:</strong> " . $e->getMessage();
    echo "\nTrace:\n" . $e->getTraceAsString();
}

echo "</pre></body></html>";
