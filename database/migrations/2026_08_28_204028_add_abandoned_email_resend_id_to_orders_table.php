<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // ID del envío programado en Resend para el recordatorio de carrito
            // abandonado. Permite cancelarlo si el pedido se paga a tiempo, sin
            // depender de un cron para decidir si corresponde enviarlo o no.
            $table->string('abandoned_email_resend_id')->nullable()->after('abandoned_email_sent');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('abandoned_email_resend_id');
        });
    }
};
