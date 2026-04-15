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
        Schema::table('page_groups', function (Blueprint $table) {
            $table->string('title_en', 150)->nullable()->after('title');
            $table->string('description_en', 500)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('page_groups', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
    }
};
