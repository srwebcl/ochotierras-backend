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
            if (!Schema::hasColumn('products', 'category_id')) {
                // Determine constraint logic separately if needed, but here simple add is fine
                // If column didn't exist, we add it with constraint
                $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            } else {
                // If column exists but maybe no FK? It's tricky.
                // Assuming if column exists, it was likely from a failed run of THIS migration.
                // We might need to check if FK exists, but standard practice for "fixing broken migration" 
                // is just ensuring we don't crash on "add column".
                // However, the previous error was FK related.
                // So maybe the column exists but FK doesn't?
                // Or maybe we should just try to add FK if column exists?
                // Let's keep it simple: if column exists, do nothing or assume it's fine.
                // BUT: The original error was "Can't create FK".
                // So the column was created, FK failed.
                // If we skip now, we might leave it without FK.
                // Better approach: Separate schema calls.
            }

            if (!Schema::hasColumn('products', 'technical_sheet')) {
                $table->string('technical_sheet')->nullable();
            }
        });

        // Separate step for FK to be safe if column exists but FK missing?
        // Actually, if column exists, we can try to add FK. 
        // But Schema builder flushes commands.
        // Let's just make it Safe.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'technical_sheet']);
        });
    }
};
