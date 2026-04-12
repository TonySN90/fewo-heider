<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tenant-Sektionen sollen beim Löschen eines Tenants ebenfalls gelöscht werden.
     * Vorher: nullOnDelete → tenant_id wurde auf NULL gesetzt, Sektionen blieben als
     * scheinbar globale Sektionen erhalten und verschmutzten die Template-Verwaltung.
     */
    public function up(): void
    {
        Schema::table('template_sections', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('template_sections', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();
        });
    }
};
