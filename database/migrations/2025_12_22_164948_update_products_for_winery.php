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
            $table->integer('vintage_year')->nullable()->after('technical_sheet');
            $table->string('strain')->nullable()->after('vintage_year');
            $table->integer('units_per_box')->default(6)->after('strain');
            $table->boolean('is_pack')->default(false)->after('units_per_box');
            $table->json('technical_details')->nullable()->after('is_pack');
        });

        Schema::create('product_bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bundles');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'vintage_year',
                'strain',
                'units_per_box',
                'is_pack',
                'technical_details',
            ]);
        });
    }
};
