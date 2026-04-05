<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('icons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique(); // Material Symbol name, z.B. "beach_access"
            $table->string('label', 100);          // Lesbare Beschreibung, z.B. "Strand"
            $table->string('group', 50)->nullable(); // Gruppierung, z.B. "Natur", "Küche"
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icons');
    }
};