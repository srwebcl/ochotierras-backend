<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->text('schedule')->nullable();      // Horario de Atención
            $table->text('schedule_en')->nullable();
            $table->text('location')->nullable();       // Ubicación
            $table->text('location_en')->nullable();
            $table->string('phone_whatsapp')->nullable();  // Teléfono / WhatsApp (dígitos, ej. 56944538170)
            $table->string('whatsapp_only')->nullable();    // WhatsApp (dígitos)
            $table->string('email')->nullable();            // Correo general
            // Lista de contactos de venta: [{ title, title_en, phone, email }]
            $table->json('sales_contacts')->nullable();
            $table->timestamps();
        });

        // Fila única con los valores que hoy están hardcodeados en el footer,
        // para que nada cambie visualmente en el primer deploy.
        \Illuminate\Support\Facades\DB::table('site_settings')->insert([
            'schedule' => 'Lunes a Viernes de 08:30 a 17:30 hrs.',
            'schedule_en' => 'Monday to Friday, 8:30 AM to 5:30 PM.',
            'location' => "Ruta D 505, km 11 desde Ovalle.\nValle del Limarí, Chile.",
            'location_en' => "Route D 505, km 11 from Ovalle.\nLimarí Valley, Chile.",
            'phone_whatsapp' => '56944538170',
            'whatsapp_only' => '56532626211',
            'email' => 'contacto@ochotierras.cl',
            'sales_contacts' => json_encode([
                [
                    'title' => 'Ventas Nacional / Exportación',
                    'title_en' => 'National Sales / Export',
                    'phone' => '56995422781',
                    'email' => 'contacto@ochotierras.cl',
                ],
                [
                    'title' => 'Ventas en Chile para Colonia China',
                    'title_en' => 'China Sales',
                    'phone' => '56966552222',
                    'email' => 'yinguowen1979@gmail.com',
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
