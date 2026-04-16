<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\Mail;

echo "<h1>Prueba de Envío de Correo</h1>";

try {
    Mail::raw('Este es un correo de prueba de Ochotierras para verificar la conexión con Resend.', function ($message) {
        $message->to('contacto@srweb.cl')
                ->subject('Prueba Técnica Ochotierras');
    });
    echo "<p style='color: green;'>✅ El correo parece haberse enviado correctamente (según Laravel).</p>";
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error al enviar el correo:</p>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
