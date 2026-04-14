<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'name_en')) {
                $table->string('name_en')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'description_en')) {
                $table->text('description_en')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'short_description_en')) {
                if (Schema::hasColumn('products', 'short_description')) {
                    $table->text('short_description_en')->nullable()->after('short_description');
                } else {
                    $table->text('short_description_en')->nullable();
                }
            }
            if (!Schema::hasColumn('products', 'tasting_notes_en')) {
                if (Schema::hasColumn('products', 'tasting_notes')) {
                    $table->text('tasting_notes_en')->nullable()->after('tasting_notes');
                } else {
                    $table->text('tasting_notes_en')->nullable();
                }
            }
            if (!Schema::hasColumn('products', 'pairing_en')) {
                if (Schema::hasColumn('products', 'pairing')) {
                    $table->text('pairing_en')->nullable()->after('pairing');
                } else {
                    $table->text('pairing_en')->nullable();
                }
            }
            if (!Schema::hasColumn('products', 'service_temp_en')) {
                if (Schema::hasColumn('products', 'service_temp')) {
                    $table->string('service_temp_en')->nullable()->after('service_temp');
                } else {
                    $table->string('service_temp_en')->nullable();
                }
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name_en')) {
                if (Schema::hasColumn('categories', 'name')) {
                    $table->string('name_en')->nullable()->after('name');
                } else {
                    $table->string('name_en')->nullable();
                }
            }
            if (!Schema::hasColumn('categories', 'description_en')) {
                if (Schema::hasColumn('categories', 'description')) {
                    $table->text('description_en')->nullable()->after('description');
                } else {
                    $table->text('description_en')->nullable();
                }
            }
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_sections', 'title_en')) {
                if (Schema::hasColumn('hero_sections', 'title')) {
                    $table->string('title_en')->nullable()->after('title');
                } else {
                    $table->string('title_en')->nullable();
                }
            }
            if (!Schema::hasColumn('hero_sections', 'subtitle_en')) {
                if (Schema::hasColumn('hero_sections', 'subtitle')) {
                    $table->string('subtitle_en')->nullable()->after('subtitle');
                } else {
                    $table->string('subtitle_en')->nullable();
                }
            }
            if (!Schema::hasColumn('hero_sections', 'button_primary_text_en')) {
                if (Schema::hasColumn('hero_sections', 'button_primary_text')) {
                    $table->string('button_primary_text_en')->nullable()->after('button_primary_text');
                } else {
                    $table->string('button_primary_text_en')->nullable();
                }
            }
            if (!Schema::hasColumn('hero_sections', 'button_secondary_text_en')) {
                if (Schema::hasColumn('hero_sections', 'button_secondary_text')) {
                    $table->string('button_secondary_text_en')->nullable()->after('button_secondary_text');
                } else {
                    $table->string('button_secondary_text_en')->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en', 'short_description_en', 'tasting_notes_en', 'pairing_en', 'service_temp_en']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en']);
        });

        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'subtitle_en', 'button_primary_text_en', 'button_secondary_text_en']);
        });
    }
};
