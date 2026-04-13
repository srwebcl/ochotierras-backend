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
            $table->string('harvest_year')->nullable()->after('technical_sheet');
            $table->string('harvest_type')->nullable()->after('harvest_year');
            $table->string('origin')->nullable()->after('harvest_type');
            $table->string('vineyard_location')->nullable()->after('origin');
            $table->string('presentation')->nullable()->after('vineyard_location');
            $table->string('closure_type')->nullable()->after('presentation');
            $table->string('varietal_composition')->nullable()->after('closure_type');
            $table->string('aging_potential')->nullable()->after('varietal_composition');
            $table->string('wood_type')->nullable()->after('aging_potential');

            // Chemical Analysis
            $table->string('alcohol')->nullable()->after('wood_type');
            $table->string('residual_sugar')->nullable()->after('alcohol');
            $table->string('total_ph')->nullable()->after('residual_sugar');
            $table->string('volatile_acidity')->nullable()->after('total_ph');
            $table->string('total_acidity')->nullable()->after('volatile_acidity');

            $table->text('tasting_notes')->nullable()->after('total_acidity');
            $table->json('awards')->nullable()->after('tasting_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'harvest_year',
                'harvest_type',
                'origin',
                'vineyard_location',
                'presentation',
                'closure_type',
                'varietal_composition',
                'aging_potential',
                'wood_type',
                'alcohol',
                'residual_sugar',
                'total_ph',
                'volatile_acidity',
                'total_acidity',
                'tasting_notes',
                'awards',
            ]);
        });
    }
};
