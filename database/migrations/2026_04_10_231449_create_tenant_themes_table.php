<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('color_primary')->nullable();
            $table->string('color_primary_dark')->nullable();
            $table->string('color_secondary')->nullable();
            $table->string('color_bg_alt')->nullable();
            $table->string('color_border')->nullable();
            $table->string('color_footer_top')->nullable();
            $table->string('color_footer_bot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_themes');
    }
};
