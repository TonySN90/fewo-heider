<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_section_content', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_section_id')->constrained('template_sections')->cascadeOnDelete();
            $table->string('field_key', 50);   // z.B. "eyebrow", "title", "subtitle"
            $table->text('value')->nullable();
            $table->unique(['template_section_id', 'field_key']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_section_content');
    }
};
