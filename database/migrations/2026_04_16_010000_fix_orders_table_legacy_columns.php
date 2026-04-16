<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Si existe la columna legacy 'total', ponerle default 0 para que no bloquee inserciones
            if (Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->nullable()->change();
            }

            // Si existe la columna legacy 'address_shipping', hacerla nullable
            if (Schema::hasColumn('orders', 'address_shipping')) {
                $table->text('address_shipping')->nullable()->change();
            }

            // Si no existe 'shipping_address', crearla
            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable();
            }

            // Si no existe 'total_amount', crearla
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        // No revertir para no perder datos
    }
};
