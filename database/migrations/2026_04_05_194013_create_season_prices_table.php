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
        Schema::create('season_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->date('from');
            $table->date('to');
            $table->unsignedSmallInteger('price_per_night');
            $table->unsignedTinyInteger('min_nights')->default(1);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->string('badge_color', 20)->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('season_prices');
    }
};
