<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('template_sections', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->index('tenant_id');
            $table->dropUnique(['template_id', 'section_key']);
            $table->unique(['template_id', 'section_key', 'tenant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('template_sections', function (Blueprint $table) {
            $table->dropUnique(['template_id', 'section_key', 'tenant_id']);
            $table->dropIndex(['tenant_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->unique(['template_id', 'section_key']);
        });
    }
};
