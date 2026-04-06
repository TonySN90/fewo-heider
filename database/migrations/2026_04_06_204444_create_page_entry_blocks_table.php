<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_entry_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_entry_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('text'); // text | heading | image
            $table->longText('content')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['page_entry_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_entry_blocks');
    }
};
