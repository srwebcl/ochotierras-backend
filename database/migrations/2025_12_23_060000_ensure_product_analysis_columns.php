<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'residual_sugar')) {
                $table->string('residual_sugar')->nullable();
            }
            if (!Schema::hasColumn('products', 'total_ph')) {
                $table->string('total_ph')->nullable();
            }
            if (!Schema::hasColumn('products', 'volatile_acidity')) {
                $table->string('volatile_acidity')->nullable();
            }
            if (!Schema::hasColumn('products', 'total_acidity')) {
                $table->string('total_acidity')->nullable();
            }
            if (!Schema::hasColumn('products', 'aging_potential')) {
                $table->string('aging_potential')->nullable();
            }
            if (!Schema::hasColumn('products', 'vineyard_location')) {
                $table->string('vineyard_location')->nullable();
            }
            if (!Schema::hasColumn('products', 'varietal_composition')) {
                $table->string('varietal_composition')->nullable();
            }
            if (!Schema::hasColumn('products', 'harvest_type')) {
                $table->string('harvest_type')->nullable();
            }
            if (!Schema::hasColumn('products', 'closure_type')) {
                $table->string('closure_type')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
