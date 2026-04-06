<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('description');
        });

        Schema::table('page_entries', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
        Schema::table('page_entries', function (Blueprint $table) {
            $table->dropColumn('cover_image');
        });
    }
};
