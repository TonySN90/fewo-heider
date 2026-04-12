<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('templates')->where('slug', 'fresh_green')->update([
            'name' => 'FEWO-01',
            'slug' => 'fewo-01',
        ]);
    }

    public function down(): void
    {
        DB::table('templates')->where('slug', 'fewo-01')->update([
            'name' => 'Evergreen',
            'slug' => 'fresh_green',
        ]);
    }
};
