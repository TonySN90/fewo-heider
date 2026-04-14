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
        Schema::table('legal_pages', function (Blueprint $table) {
            $table->string('locale', 5)->default('de')->after('type');

            $table->dropUnique(['tenant_id', 'type']);
            $table->unique(['tenant_id', 'type', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_pages', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'type', 'locale']);
            $table->dropColumn('locale');
            $table->unique(['tenant_id', 'type']);
        });
    }
};
