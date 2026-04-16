<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Agregar unit_price si no existe
            if (!Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            }
            // Agregar total_price si no existe
            if (!Schema::hasColumn('order_items', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0)->after('unit_price');
            }
            // Hacer nullable las columnas legacy si existen
            if (Schema::hasColumn('order_items', 'product_name')) {
                $table->string('product_name')->nullable()->change();
            }
            if (Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 10, 2)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        // No revertir para evitar pérdida de datos
    }
};
