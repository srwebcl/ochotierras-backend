<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('is_pack');
        });

        // Se rellena con el orden que ya tenían (por id) para que nada
        // cambie de lugar en la tienda apenas se aplica esta migración —
        // el orden real solo cambia cuando alguien lo reordene a mano
        // desde el panel.
        DB::table('products')->orderBy('id')->select('id')->get()->each(function ($product, $index) {
            DB::table('products')->where('id', $product->id)->update(['sort_order' => $index]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
