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
            $table->string('badge_bg_color')->nullable()->after('badge_text');
            $table->string('badge_text_color')->nullable()->after('badge_bg_color');
            $table->string('badge_size')->default('medium')->after('badge_text_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['badge_bg_color', 'badge_text_color', 'badge_size']);
        });
    }
};
