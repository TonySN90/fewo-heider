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
        Schema::table('page_entries', function (Blueprint $table) {
            $table->string('title_en', 200)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('page_entries', function (Blueprint $table) {
            $table->dropColumn('title_en');
        });
    }
};
