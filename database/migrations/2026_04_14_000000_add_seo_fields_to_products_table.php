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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title', 60)->nullable()->after('description_en');
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->string('meta_description', 160)->nullable()->after('meta_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('products', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
        });
    }
};
