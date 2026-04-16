<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Renombrar la columna si existe con el nombre antiguo
            if (Schema::hasColumn('orders', 'address_shipping') && !Schema::hasColumn('orders', 'shipping_address')) {
                $table->renameColumn('address_shipping', 'shipping_address');
            }

            // Si no existe ninguna de las dos, la creamos
            if (!Schema::hasColumn('orders', 'shipping_address') && !Schema::hasColumn('orders', 'address_shipping')) {
                $table->text('shipping_address')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->renameColumn('shipping_address', 'address_shipping');
            }
        });
    }
};
