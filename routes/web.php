<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreBanner;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get', 'post'], '/fix-images', function (Request $request) {
    if ($request->isMethod('post')) {
        foreach ($request->input('images', []) as $productId => $imagePath) {
            Product::where('id', $productId)->update(['image' => $imagePath ?: null]);
        }
        
        foreach ($request->input('banner_images', []) as $bannerId => $imagePath) {
            StoreBanner::where('id', $bannerId)->update(['image' => $imagePath ?: null]);
        }

        return redirect('/fix-images')->with('success', '¡Imágenes vinculadas exitosamente en la base de datos!');
    }

    $products = Product::all();
    
    $files = [];
    if (Storage::disk('public')->exists('products')) {
        $files = array_merge($files, Storage::disk('public')->files('products'));
    }
    // Also grab everything directly in 'public' just in case they dropped them there natively
    $files = array_merge($files, Storage::disk('public')->files('/'));
    
    // Also check the raw public/products folder
    if (is_dir(public_path('products'))) {
        $rawFiles = scandir(public_path('products'));
        foreach($rawFiles as $rf) {
            if ($rf != '.' && $rf != '..') {
                $files[] = 'products/' . $rf;
            }
        }
    }
    // Remove duplicates
    $files = array_unique($files);

    $html = '<html><head><title>Sincronizador</title><style>body{font-family:sans-serif;padding:40px;background:#1a1a1a;color:#fff;} .card{background:#2a2a2a;padding:20px;border-radius:10px;margin-bottom:15px;display:flex;justify-content:space-between;align-items:center;} select{padding:10px;background:#333;color:white;border:1px solid #555;border-radius:5px;} button{padding:15px 30px;background:#e53e3e;color:white;border:none;border-radius:8px;cursor:pointer;font-size:18px;width:100%;}</style></head><body>';
    $html .= '<h1>🍷 Vinculador Directo de Imágenes</h1>';
    $html .= '<p>Selecciona la foto física exacta de tu cPanel para cada vino.</p>';
    
    if (session('success')) {
        $html .= '<div style="background:#38a169;padding:15px;border-radius:5px;margin-bottom:20px;">✓ '.session('success').'</div>';
    }

    $html .= '<form method="POST" action="/fix-images">';
    $html .= csrf_field();

    foreach ($products as $p) {
        $html .= '<div class="card">';
        $html .= '<div><strong>' . $p->name . '</strong><br><small style="color:#aaa;">' . $p->subtitle . '</small></div>';
        $html .= '<select name="images['.$p->id.']">';
        $html .= '<option value="">-- Sin Imagen --</option>';
        foreach ($files as $f) {
            $selected = ($p->image === $f) ? 'selected' : '';
            $html .= '<option value="'.$f.'" '.$selected.'>'.basename($f).'</option>';
        }
        $html .= '</select></div>';
    }

    $banners = StoreBanner::all();
    if ($banners->count() > 0) {
        $html .= '<h2 style="margin-top:40px;">🖼️ Banners de Tienda</h2>';
        foreach ($banners as $b) {
            $html .= '<div class="card" style="border-left: 5px solid #e53e3e;">';
            $html .= '<div><strong>' . $b->title . '</strong><br><small style="color:#aaa;">' . $b->subtitle . '</small></div>';
            $html .= '<select name="banner_images['.$b->id.']">';
            $html .= '<option value="">-- Sin Imagen --</option>';
            foreach ($files as $f) {
                // If the user uploads to the same 'products' folder or we can just list the same $files
                $selected = ($b->image === $f) ? 'selected' : '';
                $html .= '<option value="'.$f.'" '.$selected.'>'.basename($f).'</option>';
            }
            $html .= '</select></div>';
        }
    }

    $html .= '<button type="submit" style="margin-top: 30px;">Guardar y Sincronizar Todo</button>';
    $html .= '</form></body></html>';

    return $html;
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
