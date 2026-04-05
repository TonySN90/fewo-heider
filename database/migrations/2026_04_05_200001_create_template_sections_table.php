<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->string('section_key', 50);
            $table->boolean('is_visible')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->unique(['template_id', 'section_key']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_sections');
    }
};
