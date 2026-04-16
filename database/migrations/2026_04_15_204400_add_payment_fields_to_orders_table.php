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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'site_transaction_id')) {
                $table->string('site_transaction_id')->nullable()->unique();
            }
            if (!Schema::hasColumn('orders', 'payment_id')) {
                $table->string('payment_id')->nullable();
            }
            if (!Schema::hasColumn('orders', 'marketing_opt_in')) {
                $table->boolean('marketing_opt_in')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['site_transaction_id', 'payment_id', 'marketing_opt_in']);
        });
    }
};
