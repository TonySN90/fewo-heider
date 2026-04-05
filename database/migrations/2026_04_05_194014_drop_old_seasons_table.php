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
        // Daten aus old_seasons in neues Modell übertragen
        $season2026Id = DB::table('seasons')->insertGetId([
            'year'       => 2026,
            'name'       => 'Saison 2026',
            'is_active'  => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (DB::table('old_seasons')->orderBy('sort_order')->get() as $old) {
            DB::table('season_prices')->insert([
                'season_id'       => $season2026Id,
                'name'            => $old->name,
                'from'            => $old->from,
                'to'              => $old->to,
                'price_per_night' => $old->price_per_night,
                'min_nights'      => $old->min_nights,
                'sort_order'      => $old->sort_order,
                'badge_color'     => $old->badge_color,
                'created_at'      => $old->created_at,
                'updated_at'      => $old->updated_at,
            ]);
        }

        Schema::dropIfExists('old_seasons');
    }

    public function down(): void
    {
        // nicht wiederherstellbar
    }
};
