<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        $seasons = [
            ['name' => 'Vorsaison',   'from' => '2026-05-11', 'to' => '2026-06-30', 'price_per_night' => 49, 'min_nights' => 3, 'sort_order' => 1, 'badge_color' => 'blue'],
            ['name' => 'Hauptsaison', 'from' => '2026-07-01', 'to' => '2026-08-31', 'price_per_night' => 62, 'min_nights' => 3, 'sort_order' => 2, 'badge_color' => 'green'],
            ['name' => 'Nachsaison',  'from' => '2026-09-01', 'to' => '2026-09-13', 'price_per_night' => 57, 'min_nights' => 3, 'sort_order' => 3, 'badge_color' => 'orange'],
            ['name' => 'Nebensaison', 'from' => '2026-09-14', 'to' => '2026-10-06', 'price_per_night' => 49, 'min_nights' => 3, 'sort_order' => 4, 'badge_color' => 'gold'],
        ];

        foreach ($seasons as $season) {
            Season::firstOrCreate(['name' => $season['name'], 'from' => $season['from']], $season);
        }
    }
}
