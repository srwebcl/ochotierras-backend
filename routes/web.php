<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// TEMPORARY DEPLOYMENT ROUTE - DELETE AFTER USE
Route::get('/deploy-setup', function () {
    // 1. Run Migrations
    // Force option is needed in production
    \Illuminate\Support\Facades\Artisan::call('migrate --force');
    $migrationOutput = \Illuminate\Support\Facades\Artisan::output();

    // 2. Link Storage
    // Only works if symlink() is allowed on server
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $storageOutput = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        $storageOutput = "Storage link failed (might already exist or permission denied): " . $e->getMessage();
    }

    // 3. Run Seeds & Ensure Admin User
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed --force');
        // Force reset admin user
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Admin User', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );
        $seedOutput = \Illuminate\Support\Facades\Artisan::output() . "\nAdmin user 'test@example.com' password reset to 'password'.";
    } catch (\Exception $e) {
        $seedOutput = "Seeding/User Reset failed: " . $e->getMessage();
    }

    // 4. Clear Caches
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    $cacheOutput = "Caches cleared.";

    // DEBUG: Show resolved URLs
    $debugAppUrl = config('app.url');
    $debugStorageUrl = config('filesystems.disks.public.url');
    $debugRoot = \Illuminate\Support\Facades\URL::to('/');

    return "<h1>Deployment Setup Completed</h1>
            <pre>
            <strong>Debug Environment:</strong>
            APP_URL (Config): $debugAppUrl
            Storage URL: $debugStorageUrl
            Actual Root URL: $debugRoot
            <br>
            <strong>Migration Output:</strong><br>$migrationOutput
            <br>
            <strong>Seeding Output:</strong><br>$seedOutput
            <br>
            <strong>Cache Output:</strong><br>$cacheOutput
            </pre>";
});
