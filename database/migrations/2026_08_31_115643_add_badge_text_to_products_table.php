<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Etiqueta que aparece en la esquina de la tarjeta (ej. "PACK MIX",
            // "EDICIÓN LIMITADA", "NUEVO"). Si queda vacía, el frontend cae de
            // vuelta al texto automático que ya tenía (categoría/tipo para
            // vinos, "PACK MIX" para packs).
            $table->string('badge_text')->nullable()->after('accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('badge_text');
        });
    }
};
