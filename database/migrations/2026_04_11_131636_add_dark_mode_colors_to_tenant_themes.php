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
        Schema::table('tenant_themes', function (Blueprint $table) {
            $table->string('dark_color_primary')->nullable();
            $table->string('dark_color_primary_dark')->nullable();
            $table->string('dark_color_secondary')->nullable();
            $table->string('dark_color_bg')->nullable();
            $table->string('dark_color_bg_alt')->nullable();
            $table->string('dark_color_border')->nullable();
            $table->string('dark_color_footer_top')->nullable();
            $table->string('dark_color_footer_bot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tenant_themes', function (Blueprint $table) {
            $table->dropColumn([
                'dark_color_primary', 'dark_color_primary_dark', 'dark_color_secondary',
                'dark_color_bg', 'dark_color_bg_alt', 'dark_color_border',
                'dark_color_footer_top', 'dark_color_footer_bot',
            ]);
        });
    }
};
