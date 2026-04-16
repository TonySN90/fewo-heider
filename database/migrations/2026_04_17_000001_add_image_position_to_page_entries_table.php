<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_entries', function (Blueprint $table) {
            $table->string('image_position')->default('left')->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('page_entries', function (Blueprint $table) {
            $table->dropColumn('image_position');
        });
    }
};
