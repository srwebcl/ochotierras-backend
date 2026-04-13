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
        // NO-OP: This file exists to overwrite a duplicate migration file on the server.
        // The actual table creation is handled by 2025_12_19_214000_create_categories_table.php
        // which runs before this timestamp.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
